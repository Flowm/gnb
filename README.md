# Goliath National Bank - Group 12

## Virtual machine
Our submitted virtual machine has the following passwords configured:
* System user: `samurai:r8QD4bbgvByQiuqX`
* Database user: `samurai:6JEn7RhLAGaavQTx`

We only include the real passwords here, as the repository is private and will
only be deployed on a VM without direct internet access. Normally these
password would be stored completely separate from the VCS.

## Installation
The following steps are required to use the gnb application.
All steps assume your at the root directory of the project.

### Initialize git submodules
* Download the PHPMailer submodule
	`git submodule init && git submodule update`
* Fix permissions of the upload folder
	`sudo chown www-data:www-data -R project/uploads`

### Database setup
* Create the database user samurai with the correct password
```
use mysql;
SET PASSWORD FOR 'samurai'@'localhost' = PASSWORD('6JEn7RhLAGaavQTx');
FLUSH PRIVILEGES;
```
* Connect to the sql server
	`mysql -u samurai -p`
* Drop the database, and import the schema
	`drop database gnbdb; source database/gnbdb_create.sql; `
* Create the default accounts by opening the following website:
	`http://URL/gnb/database/setup.php`

### Bulk transaction processing
* Ensure the `libmysqlclient-dev` package is installed for the mysql connection to the database
* Build the ctransact programm
	`make -C project/lib/ctransact`
