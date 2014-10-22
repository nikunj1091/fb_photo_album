<?php
/*
    Start Slideshow showing photos in that album in full-screen mode.
  */
session_start();
require_once 'facebookphotoalbum.php';
//create class object
$obj = new FacebookPhotoAlbum();
//To redirect user if not logged in
if ( ! isset( $obj->session ) ) {
	header( 'Location: index.php' );
}
// get album photos 
$photos = $obj->get_album_photos( $_REQUEST['albumId'] );
?>
<!DOCTYPE html>
<html>
<head>
	<title> Album Slide Show</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"/>
	<link href="lib/photoswipe/styles.css" rel="stylesheet" type="text/css"/>
	<link href="lib/photoswipe/photoswipe.css" rel="stylesheet" type="text/css"/>
	<style type="text/css">
		#Gallery {
			display: none;
		}
	</style>

	<script src="lib/photoswipe/simple-inheritance.min.js" type="text/javascript"></script>
	<script src="lib/photoswipe/code-photoswipe-1.0.11.min.js" type="text/javascript"></script>

	<script type="text/javascript">


		document.addEventListener('DOMContentLoaded', function () {

			// Set up PhotoSwipe
			var thumbEls = Code.photoSwipe('a', '#Gallery', {autoStartSlideshow: true});
			
			Code.PhotoSwipe.Current.show(0);
			Code.PhotoSwipe.Current.addEventListener(Code.PhotoSwipe.EventTypes.onHide, function(e){
				
				window.location="http://fbphotoalbum-rtcampapp.rhcloud.com/userhome.php";
				
			});
			

		}, true);


	</script>


</head>
<body>

<div id="Gallery">

	<?php
	
if ( ! empty( $photos ) ) {
//Loop through all photos
 foreach ( $photos['data'] as $photo ) {
			echo "<a href='{$photo->source}'><img src='{$photo->source}' /></a>";
		}
	}
	?>
</div>
</body>
</html> 	