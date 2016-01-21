# Goliath National Bank - Group 12
![GNB Logo](http://frcy.org/static/gnb.svg)

## Virtual machine
Our submitted virtual machine has the following passwords configured:
* System user: `samurai:r8QD4bbgvByQiuqX`
* Database root: `root:K7NsNeKHej9EQPYQ`
* Database user: `samurai:6JEn7RhLAGaavQTx`

We only include the real passwords here, as the repository is private and will
only be deployed on a VM without direct internet access. Normally these
password would be stored completely separate from the VCS.

## Installation
The following steps are required to use the gnb application.
All steps after the dowload step assume your at the root directory of the project.

### Download project and dependencies
* Install the required software

        sudo apt-get install apache2 php5 libapache2-mod-php5 php5-mcrypt php5-mysql php5-gd mysql-server mysql-client libmysqlclient-dev

* Clone the repository and only checkout the relevant files

        cd /var/www
        git clone git@github.com:Flowm/gnb.git && cd gnb
        git config core.sparsecheckout true
		mkdir -p .git/info
		echo /index.html >> .git/info/sparse-checkout
		echo /config/ >> .git/info/sparse-checkout
		echo /project/ >> .git/info/sparse-checkout
		git checkout origin/master

* Initialize and download all external project dependencies (PHPMailer and Securimage)

		git submodule init && git submodule update

### Configure webserver
* Copy the configuration from the config dir to `/etc`
* Fix permissions of the project folders

		sudo chown -R www-data:www-data /var/www/gnb/project/tmp

### Database setup
* Add the database user samurai with the correct password
* Connect to the sql server

		mysql -u samurai -p

* Drop the database, and import the schema

		drop database gnbdb; source config/database/gnbdb_create.sql;

* Copy the database setup script to the web directory and access the corresponding webpage

		cp config/database/setup.php project/

    * Access `https://URL/setup.php`

### Bulk transaction processing
* Build the ctransact programm

		make -C project/lib/ctransact
