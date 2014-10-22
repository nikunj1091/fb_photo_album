<?php
/*
    Download one or more album according to user requested
   */
session_start();
require_once 'facebookphotoalbum.php';
$obj    = new FacebookPhotoAlbum();
$albums = array();
//retrieve array of albumid from requests
$received_array = $_REQUEST['Selected'];
	foreach($received_array as $val)
	{
		$temp = explode( "-", $val );
		$albums[] = array( $temp[0], $temp[1] );
	}
//store userid from session variable
$userid = $_SESSION['userid'];
echo $obj->make_zip( $albums, $userid );

?>
