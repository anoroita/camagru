<?php
session_start();
if (!isset($_SESSION['LOGGED_ON']) || !$_GET)
	header('location:index.php');

$_SESSION["message"] = '';

    if ($_SESSION['LOGGED_ON'])
    {
        $id = $_SESSION['ID'];
        $picname = htmlspecialchars($_GET['pic']);
        try{
            $connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $req = $connection->prepare("SELECT PhotoID FROM photos where url = :url");
            $req->execute(array(
                ':url' => $picname
            ));
            $idphoto = $req->fetch(PDO::FETCH_COLUMN, 0);
        }
        catch(PDOException $e)
        {
            echo "Couldn't write in Database: " . $e->getMessage();
        }
        try
        {
            $connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $req = $connection->prepare("SELECT LikeID FROM likes WHERE Userid = :UserID AND photoID = :photoID");
            $req->execute(array(
                ':UserID' => $id,
                ':photoID' => $idphoto
            ));
        }
        catch(PDOException $e)
        {
            echo "Couldn't write in Database: " . $e->getMessage();
        }
        if ($req->rowCount() > 0)
        {
            try
            {
                $connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $req = $connection->prepare("DELETE FROM likes WHERE UserID = :UserID AND photoID = :photoID");
                $req->execute(array(
                    ':UserID' => $id,
                    ':photoID' => $idphoto
                ));
            }
            catch(PDOException $e)
            {
                echo "Couldn't write in Database: " . $e->getMessage();
            }
            header( "refresh:0;url=gallery.php" );
        }
        else
        {
            try
            {
                $connection = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $req = $connection->prepare("INSERT INTO likes (UserID, photoID) VALUES (:UserID, :photoID)");
                $req->execute(array(
                    ':UserID' => $id,
                    ':photoID' => $idphoto
                ));
            }
            catch(PDOException $e)
            {
                echo "Couldn't write in Database: " . $e->getMessage();
            }
				header('location:gallery.php');
        }
    }
    else
    {
        echo "You need to be Logged in -to use this feature";
    }
 ?>
