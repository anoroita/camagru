<?php
	require "database.php";

	if (!file_exists("../images"))
		mkdir("../images");
	try
	{
		$connection = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$request = "CREATE DATABASE IF NOT EXISTS db_camagru";
		$request = $connection->prepare($request);
		$request->execute();
	}
	catch(PDOException $e)
	{
		echo "Error creating DataBase: " . $e->getMessage();
    }
    try
	{
		$connection = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "CREATE TABLE IF NOT EXISTS `db_camagru`.`users` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`username` VARCHAR(255) NOT NULL,
			`email` VARCHAR(255) NOT NULL,
			`conflink` VARCHAR(255),
			`activated` INT NOT NULL DEFAULT 0,
			`password` VARCHAR(255) NOT NULL,
			`avatar` VARCHAR(255),
			`resetpsw` INT NOT NULL DEFAULT 0,
			`emailcomment` INT NOT NULL DEFAULT 0,
			PRIMARY KEY (`id`));
		  ";
		$connection->exec($query);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$connection = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "CREATE TABLE IF NOT EXISTS `db_camagru`.`Photos` (
		`PhotoID` INT NOT NULL AUTO_INCREMENT,
		`UserID` INT NOT NULL,
		`username` VARCHAR(255) NOT NULL,
		`timet` DATETIME NOT NULL,
		`url` VARCHAR(255) NOT NULL,
		PRIMARY KEY (`PhotoID`));
			";
		$connection->exec($query);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$connection = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "CREATE TABLE IF NOT EXISTS `db_camagru`.`comments` (
		`CommentID` INT NOT NULL AUTO_INCREMENT,
		`photoID` INT NOT NULL,
		`author` VARCHAR(255) NOT NULL,
		`timet` DATETIME NOT NULL,
		`text` TEXT NOT NULL,
		PRIMARY KEY (`CommentID`));
			";
		$connection->exec($query);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	try
	{
		$connection = new PDO("mysql:host=localhost", $DB_USER, $DB_PASSWORD);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "CREATE TABLE IF NOT EXISTS `db_camagru`.`likes` (
		`LikeID` INT NOT NULL AUTO_INCREMENT,
		`photoID` INT NOT NULL,
		`UserID` INT NOT NULL,
		PRIMARY KEY (LikeID));
			";
		$connection->exec($query);
	}
	catch(PDOException $e)
	{
		echo "Couldn't create table: " . $e->getMessage();
	}
	$connection = null;

	try {

	} catch (Exception $e) {

	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="/sources/icons/camagru.ico" />
		<title>Database-Setup</title>
	</head>
	<body>

	</body>
</html>
