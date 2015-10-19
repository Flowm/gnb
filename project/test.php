<?php

function testPDO()
{


	try {

		$db = new PDO('mysql:host=localhost;dbname=gnbdb;charset=utf8', 'samurai', 'samurai');
		$stmt = $db->query('SELECT * FROM user');
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as &$value) {
			print_r($value);
		}

	} catch(PDOException $ex) {
	
		echo "An error occured!";
		echo $ex->getMessage();


	}
}

function testSQL()
{
	$DB_USERNAME = "samurai";
	$DB_PASSWORD = "samurai";
	$DB_NAME     = "gnbdb";
	$DB_HOST     = "localhost";

	mysql_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
	
	$db_found = mysql_select_db($DB_NAME);

	echo "Status: " . $db_found;

	
	
}



echo $DB_HOST . $DB_SCHEMA . $DB_USERNAME . $DB_PASSWORD;

//phpinfo();

testPDO();
//testSQL();









?>




<!DOCTYPE html>
<html><body>blafu</body></html>
