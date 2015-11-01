# Goliath National Bank - Group 12

## Virtual machine
Our submitted virtual machine has the following passwords configured:
* System user: `samurai:samurai`
* Database user: `samurai:samurai`

TODO: Update with the final passwords!

## Installation
The following steps are required to use the gnb application:

### Initialize git submodules
* Go into the directory of the project
	```git submodule init && git submodule update```

### Database setup
* Go into the directory of the project
* Connect to the sql server:
	```mysql -u samurai -p```
* Drop the database, and import the schema and the dummydata:
	```drop database gnbdb; source database/gnbdb_create.sql;```
* Create the default accounts by opening the following website:
	http://frcy.org:2280/gnb-alex/database/setup.php

### Bulk transaction processing
* Go to the project/lib/ctransact folder within the project
* Ensure the `libmysqlclient-dev` package is installed for the mysql connection to the database
* Execute `make`
