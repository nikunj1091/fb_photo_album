<?php
/*
    Download one or more album according to user requested
   */
session_start();
require_once 'facebookphotoalbum.php';
$obj    = new FacebookPhotoAlbum();
//retrieve array of albumid from requests
$albums = $_REQUEST['Selected'];
//store userid from session variable
$userid = $_SESSION['userid'];
echo $obj->make_zip( $albums, $userid );
?>