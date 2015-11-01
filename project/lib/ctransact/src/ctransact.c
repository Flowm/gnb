#include "ctransact.h"

//TODO: Add quoting support to csv
//TODO: Human readable errors
//TODO: Only copy structures when required
//TODO: Replace strncpy with snprintf
//TODO: Ignore first row if it contains csv header

int main(int argc, char* argv[]) {
	FILE* file;
	int ret;

	if (argc!=3)
		return 1;

	if (!(file = fopen(argv[2], "r")))
		return 2;

	if (gnb_mysql_init())
		return 3;

	char* src_acc = argv[1];
	ret = parse_csv(src_acc, file);
	fclose(file);

	//TODO: Delete file after processing?
	//remove(file);

	return ret;
}

int parse_csv(char* src_acc, FILE* fh) {
	int c;
	int invalid = 0;
	int pos = 0;
	int field = 0;
	int line = 1;
	struct transactstr trans;
	trans.offset[field++] = 0;

	while ((c = fgetc(fh)) != EOF) {
		if (c == '\r')
			continue;
		if (c == '\n') {
			if (field != 4)
				invalid = E_TRANS_FIELDSMIN;

			if (!invalid) {
				trans.buffer[pos++] = '\0';
				process_transaction(src_acc, trans);
			} else {
				printf("Ignoring invalid transaction in line %d, ERR: %d\n", line, invalid);
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

int process_transaction(char* src, struct transactstr transtr) {
	struct transaction t;
	int res;

	TDBG printf("DBG offsets 0:%d 1:%d 2:%d 3:%d\n", transtr.offset[0], transtr.offset[1], transtr.offset[2], transtr.offset[3]);
	t.src  = src;
	t.dst  = &transtr.buffer[transtr.offset[0]];
	t.sum  = &transtr.buffer[transtr.offset[1]];
	t.desc = &transtr.buffer[transtr.offset[2]];
	t.tan  = &transtr.buffer[transtr.offset[3]];

	//TODO: Add some input validation here
	printf("Processing transaction: SRC=%s DST=%s, AMOUNT=%s, DESC=%s, TAN=%s\n", t.src, t.dst, t.sum, t.desc, t.tan);
	res = gnb_mysql_do_transaction(t);
	if (!res) {
		printf("\t--> Success\n");
	} else {
		printf("\t--> Fail\n");
	}
	return res;
}

int gnb_mysql_do_transaction(struct transaction t) {
	MYSQL_RES* result;
	my_ulonglong numrows;
	MYSQL_ROW row;
	char query[MAX_QUERY_LEN];

	//TODO: Automatic approval

	// START MySQL transaction
	snprintf(query, MAX_QUERY_LEN, "START TRANSACTION");
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}

	// Get time
	char time[20];
	snprintf(query, MAX_QUERY_LEN, "SELECT NOW()");
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	row = mysql_fetch_row(result);
	strncpy(time, row[0], 20);
	mysql_free_result(result);

	// Mark TAN as used
	snprintf(query, MAX_QUERY_LEN, "UPDATE gnbdb.tan "
			"SET used_timestamp = '%s' "
			"WHERE id = '%s' AND "
				"account_id ='%s' AND "
				"used_timestamp IS NULL", time, t.tan, t.src);
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
	mysql_free_result(result);

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
				"NULL, "
				"NULL, "
				"0 "
			")", t.src, t.dst, time, t.sum, t.desc, t.tan);
	MDBG printf("QUERY: %s\n", query);
	if (gnb_mysql_do_query(query, &result, &numrows)) {
		goto rollback;
	}
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
