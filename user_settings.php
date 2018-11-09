<?php
	session_start();

	if(!isset($_SESSION['LOGGED_ON']))
		header('location:index.php');


	if (isset($_SESSION['LOGGED_ON']))
	{
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="./sources/icons/camagru.ico" />
		<title>User-Settings</title>
	</head>
	<body>
		<div class="header">
			<a href="index.php"><button class="title" name="button">CAMAGRU</button><a/>
 			<?php
 			if (isset($_SESSION['LOGGED_ON']))
 			{
				echo '<a href="user_settings.php"><button class="icon" type="button" name="Login"><img src="./sources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./sources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="logout.php"><button class="icon" type="button" name="settings"><img src="./sources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}
 			else
 			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./sources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./sources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./sources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
 			}
 			?>
		</div>
		<div id="headerusr" style="margin-top:2vw;">Hello <?php echo $_SESSION['LOGGED_ON']; ?></div>
		<div class="settings">
			<form class="" action="change_username.php" method="post">
				<input type="text" name="newname" value="" placeholder="New username" style="width:150px;height:1vw;border-radius:0.5vw;" required>
				<input type="submit" name="submit" value="change username" style="height:2vw;width:120px">
			</form>
			<form class="" action="change_email.php" method="post">
				<input type="text" name="newmail" value="" placeholder="New email" style="width:150px;height:1vw;border-radius:0.5vw;" required>
				<input type="submit" name="submit" value="change email" style="height:2vw;width:120px">
			</form>
			<form class="" action="index.html" method="post">
				<br>
					<?php
						if ($_SESSION['mailcomm'] == 1)
							echo "<a href='commoff.php'><button type='button' name='commentson'>I don't want to receive emails when my photos are commented anymore</button></a>";
						else if ($_SESSION['mailcomm'] == 0)
							echo "<a href='common.php'><button type='button' name='commentsoff'>I want to receive emails when my photos are commented </button></a>";
					?>
			</form>
		</div>
		<div class="footer">
			camagru by anoroita&copy; 2018
		</div>

	</body>
</html>


<?php
}
?>
