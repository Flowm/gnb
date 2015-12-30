//
//  main.c
//  parser_reversed
//
//  Created by Lorenzo Donini on 23/12/15.
//  Copyright (c) 2015 Lorenzo Donini. All rights reserved.
//

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include </usr/local/mysql-connector-c-6.1.6-osx10.7-x86_64/include/mysql.h>

char * sndaccount;
char * inpfile;
char * transactiontan;
FILE * fid;

//MYSQL QUERIES
char * updateActiveTan = "UPDATE ActiveTAN SET ActiveTAN Status = 0 WHERE ActiveTAN_number = ? AND ActiveTAN_accountNo = ?";
char * getAccountBal = "SELECT Account_bal FROM Account WHERE Account_no = ? and Account_bal >= ?";
char * getAccountBal2 = "SELECT Account_bal FROM Account WHERE Account_no = ?";
char * updateAccount = "UPDATE Account SET Account_bal = ? WHERE Account_no = ?";
char * insertIntoTxn = "INSERT INTO Txn (Txn_amount, Txn_TAN_used, Txn_date, Txn_SndAccountNo, Txn_RcvAccountNo, Txn_ApprovalStatus, Txn_Status, Txn_description) VALUES (?,?,?,?,?,?,?,?)";

int _mysql_real_connect(char * mysqlStruct, char* schema, char * user, char * pw, char * hostname);
int _malloc(int size);
int _rewind(FILE * f);

/* The stuff to be freed actually gets stored in var_18 every time. Which is part of a user-defined-string. WTF? */

void close_free() {
    free(sndaccount);
    free(inpfile);
    //Not freeing TAN?!
    fclose(fid);
}

void finish_with_error(MYSQL_STMT * stmt) {
    printf("Transaction failed.");
    mysql_stmt_close(stmt);
    close_free();
    exit(1);
}

int main(int argc, const char * argv[]) {
    
    //ESP rounded down, then -1488 = -1500 from ebp
    //eax, [ebp+arg_4] <-- copying from epb + 12, char ** argv
    //[esp+1500-1456], eax <-- copying argv to memory
    //var_5B0 = argv! Points to the same array as arg_4 in the assembly.
    //eax = 20
    int var_10 = 20; //[esp+1500-16] = eax
    //eax ^ eax <-- eax = 0
    
    //var_5DC is used for parameter passing
    //No idea about var_300, var_2FC. They appear to have no use at all
    char * var_300 = 0;
    char * var_2FC = 0;
    MYSQL * var_2F8 = mysql_init(NULL);
    //[esp+1500-1500] = [esp+1500-760] <-- ???
    //var_5DC = var_2F8 <-- This is actually copying the parameter
    MYSQL_STMT * var_2F4 = mysql_stmt_init(var_2F8); //Passing var_2F8 as a parameter
    char * host = "localhost";
    char * username = "root";
    char * password = "crazypassword";
    char * schema = "Banking";
    //var_24 = 52505041h = 1380995137dec = RPPA in Ascii
    //var_20 = 4445564Fh = 1145394767dec = DEVO in Ascii
    //var_1C = 0
    char var_24[12] = "APPROVED"; //supposedly var_24 to var_1C
    char * var_18 = 0; //Used to free pointers
    int var_14 = 0; //No idea
    sndaccount = malloc(200);
    inpfile = malloc(200);
    transactiontan = malloc(200);
    
    //Wrong args
    if (argc != 5) {
        printf("Not enough arguments for the program");
        free(sndaccount);
        free(inpfile);
        return 1;
    }
    //edx = argv[1]
    int var_5B4 = -1; //Used by strlen
    char * var_5D8 = NULL; //ecx used by strlen
    char * var_5D8_2 = var_5D8 + 4; //edx used by strlen
    //eax = 0
    //ecx = var_5B4
    //edi = edx
    //repne scasb
    //eax = ecx = var_5B4
    //!eax
    //ecx = lea [eax - 1]
    
    strncpy(inpfile, argv[1], strlen(argv[1]));
    strncpy(sndaccount, argv[2], strlen(argv[2]));
    strncpy(transactiontan, argv[3], strlen(argv[3]));
    
    //edx = offset "r"
    //eax = inpfile
    //var_5D8 = edx
    //var_5DC = eax
    fid = fopen(inpfile, "r");
    if (fid == 0) {
        printf("File does not exist");
        close_free();
        return 1;
    }
    
    int var_304 = 0;
    //var_5DC = eax = fid
    //MAIN LOOP
    char var_2D9; //Used to store the result of the fgetc
    //Copies result from fgetc into var_2D9 (low byte only)
    while ((var_2D9 = fgetc(fid)) != -1) {
        //Simple file symbols check
        if ((!(var_2D9 <= 47) && var_2D9 <= 57)
            || (var_2D9 == 32 ||
                var_2D9 == 46 ||
                var_2D9 == 44 ||
                var_2D9 == 59 ||
                var_2D9 == 10 ||
                var_2D9 == 13)) {
            //loc_8048D9A
            if (var_2D9 == 59) {
                var_304 += 1;
            }
        }
        else {
            printf("Wrong symbols in line.");
            close_free();
            return 1;
        }
        /*TODO: SOMETHING IS SERIOUSLY WRONG HERE. Team 7 actually has more src code inside 
         this function, but the assembly doesn't reflect it */
    }
    
    rewind(fid);
    //var_5C0, var_5C4, var_5C8 only used for parameter passing (mysql_real_connect)
    //Copying username and host into var_5D8
    //Copying password into var_5D0
    //Copying schema into var_5CC
    if (mysql_real_connect(var_2F8, host, username, password, schema, 0, NULL, 0) == 0) {
        printf("Cannot connect to database");
        finish_with_error(var_2F4);
    }
    
    char var_2D8[612]; //Query statement (inside the assembly, the variable could get 612 bytes)
    //Copying updateActiveTan offset into var_5D8+4
    //Copying 512 into var_5D8
    //Copying address of var_2D8 query into var_5DC for parameter passing
    snprintf(var_2D8, 512, updateActiveTan);
    var_5B4 = -1;
    //eax = var_2D8
    //edx = eax
    //eax = 0
    //ecx = var_5B4
    mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
    
    /* Not initialized cause I still don't know the size. Can be guessed from the XLS table though.
    Also, since the first 8 bytes are never written, I guess they are only the starting point of the structure */
    MYSQL_BIND * var_54A;
    //eax = lea[5A4]
    //ebx = eax
    //eax = 0
    //edx = 160
    //edi = ebx
    //ecx = edx
    memset(var_54A, 0, 160); //TODO: something here might need to be changed
    var_54A[0].buffer = transactiontan;
    var_54A[0].buffer_length = strlen(transactiontan);
    var_54A[0].buffer_type = MYSQL_TYPE_STRING; //Should be 254
    var_54A[1].buffer = sndaccount;
    var_54A[1].buffer_length = strlen(sndaccount);
    var_54A[1].buffer_type = MYSQL_TYPE_STRING; //254
    //eax = lea[5A4]
    //5D8 = eax <-- param 1
    //eax = 2F4
    //5DC = eax <-- param 2
    mysql_stmt_bind_param(var_2F4, var_54A);
    //eax = 2F4
    //5DC = eax <-- passing result from before as new parameter
    if (mysql_stmt_execute(var_2F4)) {
        printf("Error while updating the TAN");
        finish_with_error(var_2F4);
    }
    
    //NOP here at random xD
    while (!feof(fid)) {
        //TODO: IMPLEMENT loc_8048F8D
        //edx = "%ld,%lf;"
        //eax = fid
        //ecx = lea[324] <--- addr 324 actually contains 2 pointers, so we're only passing the pointer to that are
        //5D0 = ecx <-- param 1 (double pointer)
        //ecx = lea[30C]
        //5D8 = edx <-- param 2 (string format pointer)
        //5D8 + 4 = ecx <-- param 3 (long pointer. Yea, it's only 4 bytes -.-")
        //5DC = eax <-- param 4 (fid)
        long var_30C; //dest_account
        double var_324; //amount
        if(fscanf(fid, "%ld,%lf;",&var_30C,&var_324) != 2) {
            break;
        }
        //load 324
        //store 324 into dbl_8049CD0
        //exchange ST(0) and ST(1)
        //fucompare ST(0), ST(1)
        //store and pop ST(0)
        //setnb al
        if (var_324 >= 10000) {
            //strcpy happening here. Only 8 bytes in total for PENDING
            strcpy(var_24, "PENDING");
        }
        else {
            //9 bytes in total for APPROVED, since we also copy the \0
            strcpy(var_24, "APPROVED");
        }
        //Copying getAccountBal offset into var_5D8+4
        //Copying 512 into var_5D8
        //Copying address of var_2D8 query into var_5DC for parameter passing
        snprintf(var_2D8, 512, getAccountBal);
        mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
        
        var_54A[0].buffer = sndaccount;
        var_54A[0].buffer_length = strlen(sndaccount);
        var_54A[0].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[1].buffer = &var_324;
        var_54A[1].buffer_type = MYSQL_TYPE_DOUBLE; //Should be 5
        mysql_stmt_bind_param(var_2F4, var_54A);
        //5DC = 2F4
        if (mysql_stmt_execute(var_2F4) != 0) {
            printf("MySQL error.");
            finish_with_error(var_2F4);
        }
        
        /* Since the query returns an account balance, I guess var_31C is where the result will actually be put */
        double var_31C; //Sender balance?!
        var_54A[0].buffer = &var_31C;
        var_54A[0].buffer_type = MYSQL_TYPE_DOUBLE; //5
        mysql_stmt_bind_result(var_2F4, var_54A);
        mysql_stmt_store_result(var_2F4);
        
        if(mysql_stmt_fetch(var_2F4) == 100) {
            printf("Not enough balance in user account");
            finish_with_error(var_2F4);
        }
        
        mysql_stmt_free_result(var_2F4);
        //Copying getAccountBal2 offset into var_5D8+4
        //Copying 512 into var_5D8
        //Copyinh address of var_2D8 query into var_5DC for parameter passing
        snprintf(var_2D8, 512, getAccountBal2);
        mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
        
        var_54A[0].buffer = &var_30C;
        var_54A[0].buffer_type = MYSQL_TYPE_LONG; //Should be 3
        mysql_stmt_bind_param(var_2F4, var_54A);
        //5DC = 2F4
        if (mysql_stmt_execute(var_2F4) != 0) {
            printf("MySQL error.");
            finish_with_error(var_2F4);
        }
        
        double var_314; //Receiver balance?!
        var_54A[0].buffer = &var_314;
        var_54A[0].buffer_type = MYSQL_TYPE_DOUBLE; //5
        mysql_stmt_bind_result(var_2F4, var_54A);
        mysql_stmt_store_result(var_2F4);
        
        if(mysql_stmt_fetch(var_2F4) == 100) {
            printf("Wrong receiver account number.");
            finish_with_error(var_2F4);
        }
        
        mysql_stmt_free_result(var_2F4);
        //eax = var_24
        if (strcmp(var_24, "PENDING") == 0) {
            //Pending case - transaction amount is >= 10000
            //continues from loc_8049530
        }
        else {
            //Approved case - transaction amount is < 10000
            snprintf(var_2D8, 512, updateAccount);
            mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
            //load 31C
            //load 324
            var_31C = var_31C - var_324; //Src new balance
            var_54A[0].buffer = &var_31C;
            var_54A[0].buffer_type = MYSQL_TYPE_DOUBLE; //5
            var_54A[1].buffer = sndaccount;
            var_54A[1].buffer_length = strlen(sndaccount);
            var_54A[1].buffer_type = MYSQL_TYPE_STRING; //254
            mysql_stmt_bind_param(var_2F4, var_54A);
            if (mysql_stmt_execute(var_2F4) != 0) {
                strcpy(var_24, "REJECTED"); //Total length 9
            }
            else {
                snprintf(var_2D8, 512, updateAccount);
                mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
                //load 314
                //load 324
                var_314 = var_314 + var_324; //Dst new balance
                memset(var_54A, 0, 160);
                var_54A[0].buffer = &var_314;
                var_54A[0].buffer_type = MYSQL_TYPE_DOUBLE; //5
                var_54A[1].buffer = &var_30C;
                var_54A[1].buffer_type = MYSQL_TYPE_LONG; //3
                mysql_stmt_bind_param(var_2F4, var_54A);
                if (mysql_stmt_execute(var_2F4) != 0) {
                    //loc_80494EA
                    printf("Error with money transfer\n%s\n",var_2D8);
                    finish_with_error(var_2F4);
                }
                printf("Money transfered: %lf euro to %ld account.",var_324,var_30C);
            }
        }
        
        //loc_8049530
        time_t var_308;
        time(&var_308);
        struct tm * var_2E0 = localtime(&var_308);
        char var_74[80]; //Length 80 according to the assembly memory mappings
        strftime(var_74, 80, "%F  %X", var_2E0);
        snprintf(var_2D8, 512, insertIntoTxn);
        mysql_stmt_prepare(var_2F4, var_2D8, strlen(var_2D8));
        var_54A[0].buffer = &var_324;
        var_54A[0].buffer_type = MYSQL_TYPE_DOUBLE; //5
        var_54A[1].buffer = transactiontan;
        var_54A[1].buffer_length = strlen(transactiontan);
        var_54A[1].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[2].buffer = var_74; //timestamp
        var_54A[2].buffer_length = strlen(var_74);
        var_54A[2].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[3].buffer = sndaccount;
        var_54A[3].buffer_length = strlen(sndaccount);
        var_54A[3].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[4].buffer = &var_30C; //dest account
        var_54A[4].buffer_type = MYSQL_TYPE_LONG; //3
        var_54A[5].buffer = var_24;
        var_54A[5].buffer_length = strlen(var_24);
        var_54A[5].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[6].buffer = var_24;
        var_54A[6].buffer_length = strlen(var_24);
        var_54A[6].buffer_type = MYSQL_TYPE_STRING; //254
        var_54A[7].buffer = argv[4]; //var_5B0 + 10h
        var_54A[7].buffer_length = strlen(argv[4]);
        var_54A[7].buffer_type = MYSQL_TYPE_STRING;
        mysql_stmt_bind_param(var_2F4, var_54A);
        if (mysql_stmt_execute(var_2F4) != 0) {
            printf("Error happened while inserting transaction details to the transaction table");
            finish_with_error(var_2F4);
        }
    }
    
    
    printf("All transactions completed");
    close_free();
    mysql_stmt_close(var_2F4);
    mysql_close(var_2F8);
    
    return 0;
}
