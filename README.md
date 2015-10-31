# Goliath National Bank - Group 12

## Virtual machine
Our submitted virtual machine has the following passwords configured:
* System user: `samurai:samurai`
* Database user: `samurai:samurai`

TODO: Update with the final passwords!

## Installation
The following steps are required to use the gnb application:

### Database setup
* Go into the directory of the project
* Connect to the sql server:
	```mysql -u samurai -p```
* Drop the database, and import the schema and the dummydata:
	```drop database gnbdb; source database/gnbdb_create.sql; source database/dummydata.sql;```

### Bulk transaction processing
* Go to the project/lib/ctransact folder within the project
* Execute `make`
