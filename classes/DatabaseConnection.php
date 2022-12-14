<?php

require_once 'Configuration.php';

class DatabaseConnection{

	public static function connect(){
		Configuration::configure();

		$servername = SERVER_NAME;
		$username = DB_USER;
		$password = DB_PASSWORD;
		$db= DATABASE;

		try {
			$connection = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
			// set the PDO error mode to exception
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		return $connection;
	}
}