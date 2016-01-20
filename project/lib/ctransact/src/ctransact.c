#include "ctransact.h"

//TODO: Add quoting support to csv
//TODO: Only copy structures when required

int main(int argc, char* argv[]) {
	FILE* file;
	int ret;
	char time[MAX_TINFO_LEN];

	if (argc!=4) {
		printf("Missing transaction arguments\n");
		return 1;
	}

	char* src_acc = argv[1];
	char* tan = argv[2];
	if (!(file = fopen(argv[3], "r"))) {
		printf("Error opening transaction file\n");
		return 2;
	}

	if (gnb_mysql_init()) {
		printf("Error establishing database connection\n");
		return 3;
	}

	if (gnb_mysql_gettime(time)) {
		printf("Error getting basic database information\n");
		return 4;
	}

	ret = parse_csv(src_acc, tan, time, file);
	fclose(file);

	return ret;
}

int parse_csv(char* src_acc, char* tan, char* time, FILE* fh) {
	int c;
	int eof = 0;
	int invalid = 0;
	int pos = 0;
	int field = 0;
	int line = 1;
	struct transactstr trans;
	trans.offset[field++] = 0;

	while (!eof) {
		c = fgetc(fh);
		if (c == EOF)
			eof = 1;
		if (c == '\r')
			continue;
		if (c == '\n' || eof) {
			if (field != MAX_TRANS_FIELDS)
				invalid = E_TRANS_FIELDSMIN;

			if (!invalid) {
				trans.buffer[pos++] = '\0';
				process_transaction(src_acc, tan, time, trans);
			} else if (eof && field < 2) {
				continue;
			} else {
				printf("Ignoring line %d due to format error.\n", line);
			}

			// Start parsing next line
			invalid = 0;
			pos = 0;
			field = 0;
			line++;
			trans.offset[field++] = 0;
			continue;
		} else if (pos > MAX_TRANS_LEN-2) {
			invalid = E_TRANS_LENGTH;
			continue;
		}

		if (c == ',') {
			if (field > MAX_TRANS_FIELDS-1) {
				invalid = E_TRANS_FIELDSMAX;
				continue;
			}
			trans.buffer[pos++] = '\0';
			trans.offset[field++] = pos;
		} else if (c >= ' ' && c <= 'z') {
			// Only allow ASCII chars 20-122
			trans.buffer[pos++] = c;
		}
	}
	return 0;
}

int process_transaction(char* src, char* tan, char* time, struct transactstr transtr) {
	struct transaction t;
	int ret;

	t.src   = src;
	t.dst   = &transtr.buffer[transtr.offset[0]];
	t.sum   = &transtr.buffer[transtr.offset[1]];
	t.desc  = &transtr.buffer[transtr.offset[2]];
	t.tan   = tan;
	t.time  = time;
	t.ap_ok = 0;
	printf("Processing: DST=%s, AMOUNT=%s, DESC=%s, TAN=%s\n", t.dst, t.sum, t.desc, t.tan);

	// Check transaction
	if (!strncmp(t.src, t.dst, 8)) {
		printf("\t--> SRC and DST can not be the same\n");
		return 1;
	}
	// Check numerical fields for validity
	if (parse_number(t.src, 0) < 10000000) {
		printf("\t--> SRC account invalid\n");
		return 1;
	}
	if (parse_number(t.dst, 0) < 10000000) {
		printf("\t--> DST account invalid\n");
		return 1;
	}
	long long sum = parse_number(t.sum, 1);
	TDBG printf("SUM: %lli\n", sum);
	if (sum < 0) {
		printf("\t--> Amount invalid\n");
		return 1;
	} else if (sum < 10000) {
		t.ap_ok = 1;
	}

	// Execute transaction
	ret = gnb_mysql_do_transaction(t);
	if (!ret) {
		printf("\t--> Success\n");
	} else {
		printf("\t--> Fail\n");
	}
	return ret;
}

int gnb_mysql_do_transaction(struct transaction t) {
	MYSQL_RES* result;
	my_ulonglong numrows;
	char query[MAX_QUERY_LEN];
	int scs = 0;

	// START MySQL transaction
	snprintf(query, MAX_QUERY_LEN, "START TRANSACTION");
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}

	// Check balance
	snprintf(query, MAX_QUERY_LEN, "SELECT balance "
		"FROM gnbdb.account_overview "
		"WHERE id = '%s' "
		"AND balance > %s", t.src, t.sum);
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	if (numrows != 1)
		goto rollback;
	mysql_free_result(result);

	// Check if user is using SCS
	snprintf(query, MAX_QUERY_LEN, "SELECT * "
		"FROM gnbdb.account "
		"JOIN user ON account.user_id = user.id "
		"WHERE user.auth_device = 2 "
			"AND account.id = %s", t.src);
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	if (numrows == 1)
		scs = 1;
	mysql_free_result(result);

	// Check TAN and mark it as used for non scs transactions
	if (!scs) {
		snprintf(query, MAX_QUERY_LEN, "SELECT * "
				"FROM gnbdb.tan "
				"WHERE id = '%s' "
					"AND account_id = '%s' "
					"AND ( used_timestamp IS NULL "
						"OR used_timestamp = '%s' )",
				t.tan, t.src, t.time);
		MDBG printf("QUERY: %s\n", query);
		if (gnb_mysql_do_query(query, &result, &numrows)) {
			goto rollback;
		}
		if (numrows != 1)
			goto rollback;
		mysql_free_result(result);

		snprintf(query, MAX_QUERY_LEN, "UPDATE gnbdb.tan "
				"SET used_timestamp = '%s' "
				"WHERE id = '%s' "
					"AND account_id = '%s' "
					"AND ( used_timestamp IS NULL "
						"OR used_timestamp = '%s' )",
				t.time, t.tan, t.src, t.time);
		MDBG printf("QUERY: %s\n", query);
		if (gnb_mysql_do_query(query, &result, &numrows)) {
			goto rollback;
		}
		mysql_free_result(result);
	}

	// Automatically approve transactions below 10000
	if (t.ap_ok == 1) {
		snprintf(t.ap_time, MAX_TINFO_LEN, "'%s'", t.time);
		snprintf(t.ap_byid, MAX_TINFO_LEN, "'1'");
	} else {
		snprintf(t.ap_time, MAX_TINFO_LEN, "NULL");
		snprintf(t.ap_byid, MAX_TINFO_LEN, "NULL");
	}

	// Insert transaction
	snprintf(query, MAX_QUERY_LEN,
			"INSERT INTO gnbdb.transaction ( "
				"source_account_id, "
				"destination_account_id, "
				"creation_timestamp, "
				"amount, "
				"description, "
				"tan_id, "
				"approved_at, "
				"approved_by_user_id, "
				"status "
			") VALUES ( "
				"'%s', "
				"'%s', "
				"'%s', "
				"'%s', "
				"'%s', "
				"'%s', "
				"%s, "
				"%s, "
				"'%i'"
			")", t.src, t.dst, t.time, t.sum, t.desc, t.tan, t.ap_time, t.ap_byid, t.ap_ok);
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	if (numrows != 1)
		goto rollback;
	mysql_free_result(result);

	// COMMIT MySQL transaction
	snprintf(query, MAX_QUERY_LEN, "COMMIT");
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	return 0;

rollback:
	mysql_free_result(result);
	snprintf(query, MAX_QUERY_LEN, "ROLLBACK");
	MDBG printf("QUERY: %s\n", query);
	gnb_mysql_do_query(query, &result, &numrows);
	mysql_free_result(result);
	return 1;
}

int gnb_mysql_do_query(char* query, MYSQL_RES** result, my_ulonglong* rows) {
	// Execute query and check for command success independent of query type
	*result = NULL;
	*rows = 0;

	if (mysql_query(con, query)) {
		MDBG fprintf(stderr, "QUERY failed: %s\n", mysql_error(con));
		return 1;
	}
	if (!(*result = mysql_store_result(con))) {
		if (mysql_field_count(con) > 0) {
			MDBG fprintf(stderr, "QUERY result empty: %s\n", mysql_error(con));
			return 2;
		}
	}
	*rows = mysql_affected_rows(con);
	MDBG printf("QUERY affected %llu rows\n", *rows);
	return 0;
}

int gnb_mysql_init() {
	// Establish a connection to the mysql server
	if (!(con = mysql_init(NULL))) {
		fprintf(stderr, "%s\n", mysql_error(con));
		return 3;
	}
	if (!mysql_real_connect(con, MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DBNAME, 0, NULL, 0)) {
		fprintf(stderr, "%s\n", mysql_error(con));
		mysql_close(con);
		return 4;
	}
	MDBG printf("MySQL client info: %s\n", mysql_get_client_info());
	MDBG printf("MySQL server info: %s\n", mysql_get_server_info(con));
	MDBG printf("MySQL connection info: %s\n\n", mysql_get_host_info(con));
	return 0;
}

int gnb_mysql_gettime(char* time) {
	// Get batch transaction timestamp
	MYSQL_RES* result;
	my_ulonglong numrows;
	MYSQL_ROW row;
	char query[MAX_QUERY_LEN];

	snprintf(query, MAX_QUERY_LEN, "SELECT NOW()");
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows))
		return 1;
	if (numrows != 1)
		return 1;
	row = mysql_fetch_row(result);
	snprintf(time, MAX_TINFO_LEN, "%s", row[0]);
	mysql_free_result(result);
	return 0;
}

long long parse_number(char* c, int decimal) {
	//Check numerical field for validity
	long long num = 0;

	while (*c) {
		if (*c == '.') {
			if (--decimal == -1)
				return -1;
		} else if (*c >= '0' && *c <= '9' && decimal >= 0) {
			num *= 10;
			num += *c-'0';
		} else {
			return -1;
		}
		c++;
	}

	return num;
}

