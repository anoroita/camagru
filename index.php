<?php
	session_start();

?>
<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<meta charset="utf-8">
		<link rel="icon" type="image/png" src="./ressources/icons/favicon.png" />
		<title>Home</title>
	</head>
	<body>
		<div class="header">
			<a href="index.php" style=""><button class="title" name="button">CAMAGRU</button><a/>
			<?php

			//This will check if user is logged on and display relevant home page icons. (depends)
			if (isset($_SESSION['LOGGED_ON']))
			{
				echo '<a href="user.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/settings.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="logout.php"><button class="icon" type="button" name="settings"><img src="./ressources/icons/logout.png" style="width:4.5vw;height:4vw;"</img></button></a>';
			}
			else
			{
				echo '<a href="sign_in.php"><button class="icon" type="button" name="Login"><img src="./ressources/icons/logins.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="sign_up.php"><button class="icon" type="button" name="Sign up"><img src="./ressources/icons/registericon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
				echo '<a href="gallery.php"><button class="icon" type="button" name="Gallery"><img src="./ressources/icons/galleryicon.png" style="width:4.5vw;height:4vw;"</img></button></a>';
			}
			?>
		</div>
		<?php

		//This will fetch logged in user's photos from database and store them in an assosiative array "result".
		if (isset($_SESSION['LOGGED_ON']))
		{
			try
			{
				$conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "simple");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$req = $conn->prepare('SELECT url FROM Photos WHERE userID = :id ORDER BY timet DESC');
				$req->execute(array(
				':id' => $_SESSION['ID']
				));
				$result = $req->fetchAll();
			}
			catch (Exception $e)
			{
				echo "Couldn't load photos : " . $e->getMessage();
			}

			/* Here, the "Choose file" and "Upload photo" button will be set and displayed, all the Filter images will be loaded 
			from ressources/filters/ and be displayed and selected using radio type selector. And set the video, canvas
			and capture button ready */
			echo '
			<div id="global">
				<div id="gauche">
				<form class="uplod" action="upload.php" method="post" enctype="multipart/form-data">
				Select image to upload:
				<input type="file" name="fileToUpload" id="fileToUpload" required>
				<input type="submit" value="Upload Image" name="submit">
					<div class="filters">
						<input type="radio" name="filter" value="one" id="one" checked/>
						<label><img src="./ressources/filters/one.png" alt="missing" class="filtersize" /></label>
						<input type="radio" name="filter" value="two" id="two"/>
						<label><img src="./ressources/filters/two.png" alt="missing" class="filtersize" /></label>
						<br>
						<input type="radio" name="filter" value="three" id="three"/>
						<label><img src="./ressources/filters/three.png" alt="missing" class="filtersize" /></label>
						<input type="radio" name="filter" value="four" id="four"/>
            <label><img src="./ressources/filters/four.png" alt="missing" class="filtersize" /></label>
            <br>
					</div>
				</form>
				<video id="video"></video>
				<button type="submit" class="cambutton" id="startbutton"><img src="./ressources/icons/photo-camera.png" style="width:4vw;height=4vw;"/></button>
				<img id="photo" />
				<canvas id="canvas" style="display:none;">No video stream...</canvas>
				</div>
				<div id="droite">';

				//Here all the captured pics will be displayed in gallery, displayed with delete button.
				foreach ($result as $value)
				{
					echo "<div class='del'>
									<img class='gallery' src='./pics/" . $value['url'] . "'/>
									<div class='delbutton'><a href='delpicture.php?pic=" . $value['url'] . "'><img src='./ressources/icons/delwhite.png' style='width:3vw;height=3vw;'/></a>
									</div>
								</div>";
				}
				echo '</div>
				</div>';
		}
		?>
		<div class="footer">
		</div>
	</body>
</html>

<?php

/* The JS script below will make video streaming for capturing of the gallery images possible*/ 

if (isset($_SESSION['LOGGED_ON']))
{
?>
<script>

/* Settting all the DOM elements and using, width and height 
and also getMedia functions to activate Webcam */

(function() {
		var streaming = false,
		video = document.querySelector('#video'),
		cover = document.querySelector('#cover'),
		canvas = document.querySelector('#canvas'),
		context = canvas.getContext('2d'),
		photo = document.querySelector('#photo'),
		startbutton = document.querySelector('#startbutton'),
		filter	= document.querySelector('#blanka'),

		width = (window.innerWidth / 5 ) ;
		height = window.innerHeight;

		navigator.getMedia = ( navigator.getUserMedia ||
								navigator.webkitGetUserMedia ||
								navigator.mozGetUserMedia ||
								navigator.msGetUserMedia);

  	navigator.getMedia(
    {
    	video: true,
    	audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
    console.log("An error occured! " + err);
    }
  );

	//Listen for canplay event and sets size of the stream.

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

	/* Captures the actual video instance from canvas, sets datastorage and appends 
	image to html div class with delete button on top. */

	function takepicture()
	{
		context.drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL("image/png");
		var tmp = new Image();
		tmp.src = data;

		var xml = new XMLHttpRequest()
		xml.open('POST', 'datastorage.php', true);
		xml.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xml.send("filter=" + document.querySelector('input[name="filter"]:checked').value + "&data=" + data);
		xml.onload = function()
		{
			var response = xml.responseText;
			photo.src = response;
			let div = document.createElement('div');
			let img = document.createElement('img');
			div.setAttribute('class', 'del');
			img.src = response;
			img.setAttribute('class', 'gallery');
			div.append(img)
			document.getElementById('droite').append(div);
		}
   }
	
	//Captures the final image.

  startbutton.addEventListener('click', function(ev)
  {
		takepicture();
  }, false);
})();
</script>
<?php
}
?>
