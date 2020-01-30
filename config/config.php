<?php
	include_once 'Install.class.php';
	include_once "database.php";

	session_start();
	$obj = new Config();
	if (!($conn = $obj->connect()))
	{
		$createDbQuery = "CREATE DATABASE `$dbname`";
		
		try {
			$dbh = new PDO("mysql:host=$servername", $username, $password);
			$dbh->exec($createDbQuery) or die("something went wrong.");

			$conn = $obj->connect();
			$conn->exec("CREATE TABLE `user`(`fname` varchar(50) not null, `lname` varchar(50) not null, `token` varchar(500), `image_path` varchar(500), `status` varchar(50) not null default 'active', `phone` varchar(20) not null, `password` varchar(255) not null, `email` varchar(50) not null PRIMARY KEY, `code` int NOT NULL, `verified` BOOLEAN NOT NULL DEFAULT false, `lat` double default 0, `lng` double default 0) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("CREATE TABLE `mechanic`(`fname` varchar(50) not null, `lname` varchar(50) not null, `token` varchar(500), `code` int, `min_fee` double not null, `rating` double default 5, `status` varchar(50) not null default 'active', `image_path` varchar(500), `id_image_path` varchar(500), `qualification_image_path` varchar(500), `phone` varchar(20) not null, `category` varchar(20) not null default 'standard', `password` varchar(255) not null, `email` varchar(50) not null PRIMARY KEY, `lat` double default 0, `lng` double default 0) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("CREATE TABLE `request`(`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `payment` varchar(50) not null default 'cash', `min_service_fee` double not null, `user_email` varchar(50) not null, `user_lat` DOUBLE not null default 0, `user_lng` DOUBLE not null default 0, `status` varchar(50) not null default 'waiting', `comment` varchar(500), `mechanic_email` varchar(50), `issue` varchar(50) NOT NULL, `make_and_model` varchar(50) NOT NULL, `image_path` varchar(500), `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `date_resolved` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("CREATE TABLE `feedback`(`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, `request_id` int not null unique, `mechanic_email` VARCHAR(50) not null, `mechanic_review` varchar(500), `mechanic_rating` DOUBLE, `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");

			$conn->exec("ALTER DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
			$conn->exec("COMMIT");

		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}
	
	if(!$conn)
	{
		die("something went wrong".mysqli_connect_error());
	}
?>
