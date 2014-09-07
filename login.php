<?php
session_start();
$url='http://localhost/FacebookPhotoAlbum/userhome.php';
if(isset($_SESSION['token']))
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
}
require_once 'lib/sdk/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
$permissions = array('user_photos');
FacebookSession::setDefaultApplication('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
//FacebookSession::setDefaultApplication('333190363521048', '64e0596fa72ef6478cfa6d3a8e3a484e');

$helper = new FacebookRedirectLoginHelper($url);
$loginUrl = $helper->getLoginUrl($permissions );

?>