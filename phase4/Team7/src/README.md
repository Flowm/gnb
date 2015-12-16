# Banking Application
## Installation Instructions
Install npm and Frontend dependencies (npm must be installed on the System)
```
npm install && bower install
```

Setup Test Database (MySQL must be installed). 
Type the following after opening the mysql client
```
CREATE DATABASE Banking;
use Banking
# Setup Database Schema
source [Absolute_Path_To_Project]/Model_for_Banking_System_create.sql
# Insert Test Data
source [Absolute_Path_To_Project]/Setup_TestDatabase.sql
```
The Databse is now ready to use: 
```
mysql> Show Tables;
+-------------------+
| Tables_in_Banking |
+-------------------+
| Account           |
| ActiveTAN         |
| Login             |
| Txn               |
+-------------------+
4 rows in set (0.00 sec)

```
Configure MySQL PHP connector in /app/api/dbconnect.php: Enter MySQL username and password

Starting the Web Application
```
grunt serve
```



In order for the application to be able to output PDFs with TANs, you need to execute:
chmod o+w api/pdfs/


