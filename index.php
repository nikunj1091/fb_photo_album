<?php
session_start();
require_once 'facebookphotoalbum.php';
//Create class object
$loginObj = new FacebookPhotoAlbum();
//Redirect user if already logged in
if ( isset( $loginObj->session ) ) {
	header( 'Location: userhome.php' );
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Facebook Photo Album</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="asset/js/jquery.js"></script>
	<!--include bootstrap and custom css-->
	<link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css"/>
	<link href="asset/css/FbAlbum.css" rel="stylesheet" type="text/css"/>

</head>

<body style="background-image: url('asset/img/furley_bg.png')">

<div class="container main center">
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Facebook Photo Albums</h4></div>
		<div class="panel-body">
			<img src="asset/img/facebook-album-Icon.png"
			     width="35%"/><?php echo "<a class='imgfbconnect' href='" . $loginObj->login_url() . "'><img   src='asset/img/fbconnect.png' width='40%' /></a>"; ?>
		</div>
	</div>
</div>
<script src="asset/js/bootstrap.js" type="text/javascript"></script>
</body>
</html>