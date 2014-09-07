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
//FacebookSession::setDefaultApplication('333190363521048', '64e0596fa72ef6478cfa6d3a8e3a484e');
if (isset($_SESSION['token'])) {
    $session = new FacebookSession($_SESSION['token']);
    try {
        $session->Validate('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
        //$session->Validate('333190363521048', '64e0596fa72ef6478cfa6d3a8e3a484e');
    } catch (FacebookAuthorizationException $e) {
        $session = '';
    }
    try {
    if (isset($session)) {
        $uid=$_SESSION["userid"];
        $fname = $uid . '.zip';
        $zip = new ZipArchive;
        $zip->open($fname, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE);
        $albums = $_REQUEST["Selected"];
        foreach ($albums as $albumId) {
            $getPhotoRequest = new FacebookRequest($session, 'GET', '/' . $albumId . '/photos');
            $getPhotoresponse = $getPhotoRequest->execute();
            $photo_graphObj = $getPhotoresponse->getGraphObject();
            $photolist = $photo_graphObj->asArray('data');           
            if (!empty($photolist)) {
                foreach ($photolist['data'] as $photo) {
                    $zip->addFromString($albumId . '/' . basename($photo->source), file_get_contents($photo->source));
                }
            }            
        }
        $zip->close();
        echo $fname;
    }
} catch (Exception $ex) {
    echo "fail";
}
}
?>