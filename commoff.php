<?php
	session_start();

	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
	try
	{
		$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$update = $conn->prepare("UPDATE users SET emailcomment = '0' WHERE username = :username");
		$update->execute(array(
			':username' => $_SESSION['LOGGED_ON']
		));
	}
	catch (Exception $e)
	{
		echo "Couldn't update : " . $e->getMessage();
	}
	$_SESSION['mailcomm'] = 0;
	header('location:user.php');

 ?>
