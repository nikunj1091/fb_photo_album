<?php
session_start();
require_once 'lib/sdk/autoload.php';
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
FacebookSession::setDefaultApplication('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
$helper = new FacebookRedirectLoginHelper('http://localhost/FacebookPhotoAlbum/userhome.php');
try {
    $session = $helper->getSessionFromRedirect();
} catch (Exception $ex) {
    
}
if (isset($_SESSION['token'])) {
    $session = new FacebookSession($_SESSION['token']);
    try {
        $session->Validate('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
    } catch (FacebookAuthorizationException $e) {
        $session = '';
    }
}
if (isset($session)) {
    $_SESSION['token'] = $session->getToken();
}
if (isset($session)) {
    $user_profile = new FacebookRequest($session, 'GET', '/me');
    $response = $user_profile->execute();
    $user_profile_graphObject = $response->getGraphObject();
    $_SESSION['userid']=$user_profile_graphObject->getProperty('id');
    $profile_picture = new FacebookRequest($session, 'GET', '/me/picture/', array('redirect' => false, 'height' => '200', 'type' => 'normal', 'width' => '200',));
    $response = $profile_picture->execute();
    $profile_picture_graphObject = $response->getGraphObject();
    $url = $profile_picture_graphObject->getProperty('url');
    $cover=$user_profile_graphObject->asArray('data');

    $albumrequest = new FacebookRequest($session, 'GET', '/me/albums');
    $response = $albumrequest->execute();
    $graphObject = $response->getGraphObject();
    $albums = array();
    $albums = $graphObject->asArray('data');
}

?>