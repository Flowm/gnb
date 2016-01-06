#include <mysql.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

// Global vars as their symbols are in the binary
static char* sndaccount;
static char* inpfile;
static char* transactiontan;
static FILE* fid;

void close_free() {
	// 0x08048a74 - 0x08048aa2 // close_free function
	free(sndaccount);
	free(inpfile);
	//free(transactiontan); MISSING!
	fclose(fid);
}

void finish_with_error(MYSQL_STMT* db_stmt) {
	// 0x08048aa3 - 0x08048acc // finish_with_error function
	puts("Transaction failed.\n");
	mysql_stmt_close(db_stmt);
	close_free();
	exit(1);
}

int main(int argc, char* argv[]) {
	// 0x08048ad1 - 0x08048b98 // Variable declaration and init
	void* var_2dc = 0; // 0x2dc UNUSED?
	void* var_2e0 = 0; // 0x2eo UNUSED?
	MYSQL* db_conn = mysql_init(NULL); // 0x2e4
	MYSQL_STMT* db_stmt = mysql_stmt_init(db_conn); // 0x2e8

	char* db_host = "localhost"; // 0x2ec
	char* db_user = "root";	// 0x2f0
	char* db_pass = "crazypassword"; // 0x2f4
	char* db_name = "Banking"; // 0x2f8
	void* var_5bc; // 0x5bc UNUSED?
	void* var_5c0 = 0; // 0x5c0 UNUSED?
	void* var_5c4 = 0; // 0x5c4 UNUSED?
	void* var_5c8 = 0; // 0x5c8 UNUSED?

	// 0x08048b98 - 0x08048bc6 // Malloc for static ptrs
	sndaccount = malloc(200);
	inpfile = malloc(200);
	transactiontan = malloc(200);

	if (argc != 5) {
		puts("Not enough arguments for the program\n");
		free(sndaccount);
		free(inpfile);
		//free(transactiontan); MISSING!
		exit(1);
	}

	// 0x08048bd5 - 0x08048ca5
	strncpy(sndaccount, argv[1], strlen(argv[1]));
	strncpy(inpfile, argv[2], strlen(argv[2]));
	strncpy(transactiontan, argv[3], strlen(argv[3]));

	// 0x08048caa - 0x08048cce
	if (!(fid = fopen(inpfile, "r"))) {
		puts("File does not exist\n");
		close_free();
		exit(1);
	}

	// 0x08048d20 - 0x08048dc8 // Check Loop
	char c; // 0x303
	while ((c = fgetc(fid)) != EOF) {
		// 0x08048d2d - 0x08048d95 Filter invalid characters
		if (c <= '0' || c >= '9') { // jle 0x2f + ! jle 0x39
			if (!(c == ' ' || c == '.' || c == ',' || c == ';' || c == '\n' || c == '\r')) {
				puts("Wrong symbols in line.\n");
				close_free();
				exit(1);
			}
		}
	}

	// 0x08048dce - 0x08048e4d
	rewind(fid);
	if (!mysql_real_connect(db_conn, db_host, db_user, db_pass, db_name, 0, 0, 0)) {
		puts("Cannot connect to database\n");
		finish_with_error(db_stmt);
	}

	// 0x08048e4d - 0x08048eab
	char query[512]; // 0x304 - Length based on stack layout and snpritf max length
	snprintf(query, 512, "UPDATE ActiveTAN SET ActiveTAN_Status = 0 WHERE ActiveTAN_number = ? AND ActiveTAN_accountNo = ?");
	mysql_stmt_prepare(db_stmt, query, strlen(query));

	MYSQL_BIND params[8]; // 0x038...
	// 0x08048eb0 - 0x08048ec4
	memset(params, 0, sizeof(params));

	// 0x08048ec6 - 0x08048efa
	params[0].buffer_type = MYSQL_TYPE_STRING; // 0xfe = 254 = MYSQL_TYPE_STRING from mysql_com.h
	params[0].buffer = transactiontan;
	params[0].buffer_length = strlen(transactiontan);

	// 0x08048efe - 0x08048f38
	params[1].buffer_type = MYSQL_TYPE_STRING;
	params[1].buffer = sndaccount;
	params[1].buffer_length = strlen(sndaccount);

	// 0x08048f3f - 0x08048f88
	mysql_stmt_bind_param(db_stmt, params);
	if (mysql_stmt_execute(db_stmt)) {
		puts("Error while updating the TAN\n");
		finish_with_error(db_stmt);
	}

	// 0x080497ee - 0x08049804 // Loop
	while (!feof(fid)) {
		long tx_dst; // 0x2d0
		double tx_sum; // 0x2b8
		char* tx_state;	// 0x5b8

		// 0x08048f8d - 0x08048fbc // Scan for transaction
		if (fscanf(fid, "%ld,%lf;", &tx_dst, &tx_sum) != 2) {
			break;
		}

		// 0x08048fc2 - 0x0804900e // Setting TX state (useless strcpy omitted)
		if (tx_sum >= 10000) {
			tx_state = "PENDING";
		} else {
			tx_state = "APPROVED";
		}

		// 0x08049011 - 0x08049105 // Query account balance
		snprintf(query, 512, "SELECT Account_bal FROM Account WHERE Account_no= ? and Account_bal >= ?");
		mysql_stmt_prepare(db_stmt, query, strlen(query));

		params[0].buffer_type = MYSQL_TYPE_STRING;
		params[0].buffer = sndaccount;
		params[0].buffer_length = strlen(sndaccount);
		params[1].buffer_type = MYSQL_TYPE_DOUBLE; // 0x5 = 5 = MYSQL_TYPE_DOUBLE from mysql_com.h
		params[1].buffer = &tx_sum;

		mysql_stmt_bind_param(db_stmt, params);
		if (mysql_stmt_execute(db_stmt)) {
			puts("MySQL error.\n");
			finish_with_error(db_stmt);
		}

		// 0x0804910a - 0x0804916d // Verify results (account exists and has enough balance) and store balance
		double src_balance = 0; // 0x2c0
		params[0].buffer_type = MYSQL_TYPE_DOUBLE;
		params[0].buffer = &src_balance;
		mysql_stmt_bind_result(db_stmt, params);
		mysql_stmt_store_result(db_stmt);
		if (mysql_stmt_fetch(db_stmt) == MYSQL_NO_DATA) { // 100 = MYSQL_NO_DATA from mysql.h
			puts("Not enough balance in user account\n");
			finish_with_error(db_stmt);
		}

		// 0x08049172 - 0x08049237 // Query dst account balance
		mysql_stmt_free_result(db_stmt);

		snprintf(query, 512, "SELECT Account_bal FROM Account WHERE Account_no= ?");
		mysql_stmt_prepare(db_stmt, query, strlen(query));

		params[0].buffer_type = MYSQL_TYPE_LONG; // 0x3 = 3 = MYSQL_TYPE_DOUBLE from mysql_com.h
		params[0].buffer = &tx_dst;

		mysql_stmt_bind_param(db_stmt, params);
		if (mysql_stmt_execute(db_stmt)) {
			puts("MySQL error.\n");
			finish_with_error(db_stmt);
		}

		// 0x0804923c - 0x0804929f // Verify results (account exists) and store balance
		double dst_balance = 0; // 0x2c8
		params[0].buffer_type = MYSQL_TYPE_DOUBLE;
		params[0].buffer = &dst_balance;
		mysql_stmt_bind_result(db_stmt, params);
		mysql_stmt_store_result(db_stmt);
		if (mysql_stmt_fetch(db_stmt) == MYSQL_NO_DATA) { // 100 = MYSQL_NO_DATA from mysql.h
			puts("Wrong receiver account number.\n");
			finish_with_error(db_stmt);
		}

		// 0x080492a4 - 0x080492dd // Process approved transactions
		mysql_stmt_free_result(db_stmt);

		if (strcmp(tx_state, "PENDING")) {
			// 0x080492e3 - 0x080493d9 // Update src balance
			snprintf(query, 512, "UPDATE Account SET Account_bal=? WHERE Account_no=?");
			mysql_stmt_prepare(db_stmt, query, strlen(query));

			src_balance = src_balance - tx_sum;

			params[0].buffer_type = MYSQL_TYPE_DOUBLE;
			params[0].buffer = &src_balance;
			params[1].buffer_type = MYSQL_TYPE_STRING;
			params[1].buffer = sndaccount;
			params[1].buffer_length = strlen(sndaccount);

			mysql_stmt_bind_param(db_stmt, params);
			if (mysql_stmt_execute(db_stmt)) {
				// 0x08049513 - 0x0804952d // Error: Reject transaction (again without useless strcpy)
				tx_state = "REJECTED";

			} else {
				// 0x080493df - 0x080494c3 // Ok: Update dst balance
				snprintf(query, 512, "UPDATE Account SET Account_bal=? WHERE Account_no=?");
				mysql_stmt_prepare(db_stmt, query, strlen(query));

				dst_balance = dst_balance + tx_sum;

				params[0].buffer_type = MYSQL_TYPE_DOUBLE;
				params[0].buffer = &dst_balance;
				params[1].buffer_type = MYSQL_TYPE_LONG;
				params[1].buffer = &tx_dst;

				mysql_stmt_bind_param(db_stmt, params);
				if (mysql_stmt_execute(db_stmt)) {
					// 0x080494ea - 0x08049511 // Error
					printf("Error with money transfer\n%s\n", query);
					finish_with_error(db_stmt);

				} else {
					// 0x080494c5 - 0x080494e8 // OK
					printf("Money transfered: %lf euro to %ld account.\n", tx_sum, tx_dst);

				}
			}
		}

		// 0x08049530 - 0x080497ec // Insert transaction into transaction table
		time_t seconds; // 0x2d4
		struct tm *lct; // 0x2fc
		char timestr[80]; // 0x568

		time(&seconds);
		lct = localtime(&seconds);
		strftime(timestr, 80, "%F  %X", lct);

		snprintf(query, 512, "INSERT INTO Txn ( Txn_amount, Txn_TAN_used, Txn_date, Txn_SndAccountNo, Txn_RcvAccountNo, Txn_ApprovalStatus, Txn_Status, Txn_description) VALUES (?,?,?,?,?,?,?, ?)");
		mysql_stmt_prepare(db_stmt, query, strlen(query));

		params[0].buffer_type = MYSQL_TYPE_DOUBLE;
		params[0].buffer = &tx_sum;

		params[1].buffer_type = MYSQL_TYPE_STRING;
		params[1].buffer = transactiontan;
		params[1].buffer_length = strlen(transactiontan);

		params[2].buffer_type = MYSQL_TYPE_STRING;
		params[2].buffer = timestr;
		params[2].buffer_length = strlen(timestr);

		params[3].buffer_type = MYSQL_TYPE_STRING;
		params[3].buffer = sndaccount;
		params[3].buffer_length = strlen(sndaccount);

		params[4].buffer_type = MYSQL_TYPE_LONG;
		params[4].buffer = &tx_dst;

		params[5].buffer_type = MYSQL_TYPE_STRING;
		params[5].buffer = tx_state;
		params[5].buffer_length = strlen(tx_state);

		params[6].buffer_type = MYSQL_TYPE_STRING;
		params[6].buffer = tx_state;
		params[6].buffer_length = strlen(tx_state);

		char* tx_desc = argv[4]; // 0x03c
		params[7].buffer_type = MYSQL_TYPE_STRING;
		params[7].buffer = tx_desc;
		params[7].buffer_length = strlen(tx_desc);

		mysql_stmt_bind_param(db_stmt, params);
		if (mysql_stmt_execute(db_stmt)) {
			puts("Error happend while inserting transaction details to the transaction table\n");
			finish_with_error(db_stmt);
		}
	}

	// 0x08049807 - 0x0804983d
	puts("All transactions completed\n");
	close_free();
	mysql_stmt_close(db_stmt);
	mysql_close(db_conn);
	exit(0);
}
