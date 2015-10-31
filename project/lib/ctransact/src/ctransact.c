#include "ctransact.h"

//TODO: Add quoting support to csv
//TODO: Human readable errors

int main(int argc, char* argv[]) {
	FILE* file;
	int ret;

	if (argc!=2) {
		return 1;
	}
	if (!(file = fopen(argv[1], "r"))) {
		return 2;
	}

	ret = parse_csv(file);
	fclose(file);

	//TODO: Delete file after processing?
	//remove(file);

	return ret;
}

int parse_csv(FILE* fh) {
	int c;
	int invalid = 0;
	int pos = 0;
	int field = 0;
	int line = 1;
	struct transaction trans;
	trans.offset[field++] = 0;

	while ((c = fgetc(fh)) != EOF) {
		if (c == '\r')
			continue;
		if (c == '\n') {
			if (field != 4)
				invalid = E_TRANS_FIELDSMIN;

			if (!invalid) {
				trans.buffer[pos++] = '\0';
				process_transaction(trans);
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
		} else if (c >= '.' && c <= 'z') {
			// Only allow ASCII chars 46-122
			trans.buffer[pos++] = c;
		}
	}
	return 0;
}

int process_transaction(struct transaction trans) {
	DBG printf("DBG offsets 0:%d 1:%d 2:%d 3:%d\n", trans.offset[0], trans.offset[1], trans.offset[2], trans.offset[3]);
	char * dst  = &trans.buffer[trans.offset[0]];
	char * sum  = &trans.buffer[trans.offset[1]];
	char * desc = &trans.buffer[trans.offset[2]];
	char * tan  = &trans.buffer[trans.offset[3]];
	printf("Sending DST:%s, AMOUNT:%s, DESC:%s, TAN:%s\n", dst, sum, desc, tan);
	return 0;
}
