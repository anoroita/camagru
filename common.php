<?php
	session_start();

	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$update = $connection->prepare("UPDATE users SET emailcomment = '1' WHERE username = :username");
		$update->execute(array(
			':username' => $_SESSION['LOGGED_ON']
		));
	}
	catch (Exception $e)
	{
		echo "Couldn't update : " . $e->getMessage();
	}
	$_SESSION['mailcomm'] = 1;
	header('location:user_settings.php');
 ?>
