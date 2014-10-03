<?php
/*
    Download one or more album according to user requested
   */
session_start();
require_once 'facebookphotoalbum.php';
$obj    = new FacebookPhotoAlbum();
$albums = $_REQUEST['Selected'];
$userid = $_SESSION['userid'];
echo $obj->make_zip( $albums, $userid );
?>