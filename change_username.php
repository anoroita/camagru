<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
		try
		{
			$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $connection->prepare("SELECT * FROM users WHERE username = :username");
			$update->execute(array(
				':username' => $_POST['newname']));
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		if ($update->rowCount() == 0 && $_POST['newname'])
		{
			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update = $connection->prepare("UPDATE Photos SET username = :newusername WHERE username = :username");
				$update->execute(array(
					':newusername' => htmlspecialchars($_POST['newname']),
					':username' => $_SESSION['LOGGED_ON']
				));
			}
			catch (Exception $e)
			{
				echo "Couldn't update : " . $e->getMessage();
			}
			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update = $connection->prepare("UPDATE users SET username = :newusername WHERE username = :username");
				$update->execute(array(
					':newusername' => htmlspecialchars($_POST['newname']),
					':username' => $_SESSION['LOGGED_ON']
				));
			}
			catch (Exception $e)
			{
				echo "Couldn't update : " . $e->getMessage();
			}
			$_SESSION['LOGGED_ON'] = htmlspecialchars($_POST['newname']);
			header('location:user_settings.php');
		}
		else {
			echo "Please update username in settings";
			header( "refresh:2;url=user_settings.php" );
		}
 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="./sources/icons/camagru.ico" />
		<title>Change-Username</title>
	</head>
	<body>

	</body>
</html>
