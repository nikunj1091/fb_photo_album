<?php
//require_once 'login.php';
session_start();
$url='http://fbphotoalbum-rtcampapp.rhcloud.com/userhome.php';
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
$loginUrl = $helper->getLoginUrl($permissions);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="asset/js/jquery.js"></script>
        
        <link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="asset/css/FbAlbum.css" rel="stylesheet" type="text/css"/>
    
    </head>
    
    <body style="background-image: url('asset/img/furley_bg.png')" >
        <div class="container main">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Facebook Photo Albums</h4></div>
                <div class="panel-body">
                    <img src="asset/img/facebook-album-Icon.png" class="img-rounded" width="35%" /><?php echo '<a class="imgfbconnect" href="' . $loginUrl . '"><img  src="asset/img/fbconnect.png" /></a>'; ?>
                </div>
            </div> 
        </div>
        <script src="asset/js/bootstrap.js" type="text/javascript"></script>
</body>
</html>