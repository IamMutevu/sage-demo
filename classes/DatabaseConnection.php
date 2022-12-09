<?php

class DatabaseConnection{

	public static function connect(){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db= "google_cloud";

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