
<?php
	session_start();
	if (!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');
		try
		{
			$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $connection->prepare("SELECT * FROM users WHERE email = :newmail");
			$update->bindParam(':newmail', $_POST['newmail']);
			$update->execute();
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		if ($update->rowCount() == 0 && $_POST['newmail'] && filter_var($email = $_POST['newmail'], FILTER_VALIDATE_EMAIL))
		{
		try
		{
			$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update = $connection->prepare("UPDATE users SET email = :newmail WHERE username = :username");
			$update->execute(array(
				':newmail' => $_POST['newmail'],
				':username' => $_SESSION['LOGGED_ON']
			));
		}
		catch (Exception $e)
		{
			echo "Couldn't update : " . $e->getMessage();
		}
		header('location:user_settings.php');
		}
		else
		{
			echo "Please update email in settings";
			header( "refresh:2;url=user_settings.php" );
		}
 ?>

 <!DOCTYPE html>
 <html>
 	<head>
 		<meta charset="utf-8">
 		<link rel="icon" type="image/png" href="./sources/icons/camagru.ico" />
 		<title>Change-Email</title>
 	</head>
 	<body>

 	</body>
 </html>
