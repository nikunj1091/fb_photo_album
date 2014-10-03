<?php
/*
    Start Slideshow showing photos in that album in full-screen mode.
  */
session_start();
require_once 'facebookphotoalbum.php';
$obj = new FacebookPhotoAlbum();
if ( ! isset( $obj->session ) ) {
	header( 'Location : index.php', true, $permanent ? 301 : 302 );
}
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

			// Set up PhotoSwipe, setting "preventHide: true"
			var thumbEls = Code.photoSwipe('a', '#Gallery', {preventHide: true, autoStartSlideshow: true});

			Code.PhotoSwipe.Current.show(0);

		}, true);


	</script>


</head>
<body>

<div id="Gallery">

	<?php
if ( ! empty( $photos ) ) {
 foreach ( $photos['data'] as $photo ) {
			echo "<a href='{$photo->source}'><img src='{$photo->source}' /></a>";
		}
	}
	?>
</div>
</body>
</html> 	