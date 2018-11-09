<?php
session_start();

if (isset($_SESSION['LOGGED_ON']))
	header('location:index.php');
else
{
	$_SESSION['message'] = '';
	$_SESSION['login_success'] = '';
	$_SESSION['login_err'] = '';
	$_SESSION['ID'] = '';
	$_SESSION['LOGGED_ON'] = NULL;
	$_SESSION['mailcomm'] = '';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$password = hash("sha512", $_POST['password']);
		$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$req = $con->prepare("SELECT username FROM users WHERE username = :username AND password = :password AND activated = '1'");
		$req->execute(array(
			':username' => $_POST['username'],
			':password' => $password
			));
		if ($req->rowCount() > 0)
		{

			$_SESSION['login_success'] = "You are logged on " . $_POST['username'];
			$_SESSION['LOGGED_ON'] = $_POST['username'];
			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$req = $connection->prepare("SELECT id FROM users where username = :username");
				$req->execute(array(
					':username' => $_SESSION['LOGGED_ON']
				));
				$id = $req->fetch(PDO::FETCH_COLUMN, 0);
			}
			catch(PDOException $e)
			{
				echo "Couldn't write in Database: " . $e->getMessage();
				$_SESSION['login_success'] = '';
				$_SESSION['LOGGED_ON'] =	NULL;
			}
			$_SESSION['ID'] = $id;
			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$req = $connection->prepare("SELECT emailcomment FROM users where id = :id");
				$req->execute(array(
					':id' => $_SESSION['ID']
				));
				$emailcomment = $req->fetch(PDO::FETCH_COLUMN, 0);
			}
			catch (Exception $e)
			{
				echo "Couldn't get variable : " . $e->getMessage();
			}
			$_SESSION['mailcomm'] = $emailcomment;
			header( "refresh:1;url=index.php");
		}
		else
		{
			$_SESSION['login_err'] = "Username or password incorrect";
		}

	}
	catch (PDOexception $e)
	{
		echo "couldn't log you in : " . $e->getMessage();
	}
}

 ?>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="./sources/icons/camagru.ico" />
		<title>Sign-in</title>
	</head>
	<body>
		<div class="header">
			<a href="index.php"><button class="title" name="button">CAMAGRU</button><a/>
 			<?php

 			if (isset($_SESSION['LOGGED_ON']))
 			{
				echo '<a href="user_settings.php"><button class="icon" type="button" name="settings"><img src="./sources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./sources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="logout.php"><button class="icon" type="button" name="Login"><img src="./sources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>';
			}

 			else
 			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./sources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./sources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./sources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}

 			?>
		</div>
		<div class="main">
		<form class="modal-content" action="sign_in.php" method="post">
			<div style = "padding:14%">
				<div class="log_error"><?= $_SESSION['login_err'] ?></div>
				<div class="log_succes"><?= $_SESSION['login_success'] ?></div>
				<label class=login_><b>Username</b></label>
				<input type="text" placeholder="Enter user name" name="username" required>

				<label class="login_"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="password" required>

				<div class="clearfix" style="text-align: center;">
				<a href="resetpsw.php"><button class="signup" type="button" name="resetpsw">I forgot my password</button></a>

				<button type="submit" class="signup" name="clickme" style= "margin-left: 2%;margin-top: 2%";>Sign in</button>
				</div>
		</div>
		</form>
		</div>
		<div class="footer">
			camagru by anoroita&copy; 2018
		</div>

	</body>
</html>
