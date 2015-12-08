#include <stdio.h>
#include <string.h>

#include <mysql.h>

//#define TDEBUG
//#define MDEBUG

#ifdef TDEBUG
 #define TDBG if(1)
#else
 #define TDBG if(0)
#endif
#ifdef MDEBUG
 #define MDBG if(1)
#else
 #define MDBG if(0)
#endif

#define MYSQL_HOSTNAME	"localhost"
#define MYSQL_USERNAME	"samurai"
#define MYSQL_PASSWORD	"6JEn7RhLAGaavQTx"
#define MYSQL_DBNAME	"gnbdb"

#define E_TRANS_LENGTH		1
#define E_TRANS_FIELDSMAX	2
#define E_TRANS_FIELDSMIN	3

#define MAX_QUERY_LEN 1024
#define MAX_TRANS_LEN 512
#define MAX_TRANS_FIELDS 3
#define MAX_TINFO_LEN 32

struct transactstr {
	char buffer[MAX_TRANS_LEN];
	int offset[MAX_TRANS_FIELDS];
};
struct transaction {
	char* src;
	char* dst;
	char* sum;
	char* desc;
	char* tan;
	char* time;
	int  ap_ok;
	char ap_time[MAX_TINFO_LEN];
	char ap_byid[MAX_TINFO_LEN];
};

int parse_csv(char*, char*, char*, FILE*);
int process_transaction(char*, char*, char*, struct transactstr);

static MYSQL* con;
int gnb_mysql_do_transaction(struct transaction);
int gnb_mysql_do_query(char*, MYSQL_RES**, my_ulonglong*);
int gnb_mysql_init();
int gnb_mysql_gettime(char*);

long long parse_number(char*, int);
