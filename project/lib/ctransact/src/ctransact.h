#include <stdio.h>

#define DEBUG
#ifdef DEBUG
 #define DBG if(1)
#else
 #define DBG if(0)
#endif

#define E_TRANS_LENGTH		1
#define E_TRANS_FIELDSMAX	2
#define E_TRANS_FIELDSMIN	3

#define MAX_TRANS_LEN 512
#define MAX_TRANS_FIELDS 4
struct transaction {
	char buffer[MAX_TRANS_LEN];
	int offset[MAX_TRANS_FIELDS];
};

int parse_csv(FILE* fh);
int process_transaction(struct transaction);
