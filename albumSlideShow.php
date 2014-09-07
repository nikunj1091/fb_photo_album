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
if (isset($_SESSION['token'])) {
    $session = new FacebookSession($_SESSION['token']);
    try {
        $session->Validate('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
    } catch (FacebookAuthorizationException $e) {
        $session = '';
    }
      $request = new FacebookRequest($session,'GET','/'.$_REQUEST["albumId"].'/photos');
             $response1 = $request->execute();
             $graphObject1 = $response1->getGraphObject();
             $data1 = array();
             $data1 = $graphObject1 -> asArray('data');
            
             
}
?>
<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Album</title>
    <link href="lib/maximage/css/jquery.maximage.min.css" rel="stylesheet" type="text/css"/>
 	
    <style type="text/css" media="screen">			
			#maximage {
/*				position:fixed !important;*/
			}
			
			/*Set my logo in bottom left*/
			#logo {
				bottom:30px;
				height:auto;
				left:30px;
				position:absolute;
				width:34%;
				z-index:1000;
			}
			#logo img {
				width:100%;
			}

			#arrow_left, #arrow_right {
				bottom:30px;
				height:67px;
				position:absolute;
				right:30px;
				width:36px;
				z-index:1000;
			}
			#arrow_left {
				right:86px;
			}

			#arrow_left:hover, #arrow_right:hover {
				bottom:29px;
			}
			#arrow_left:active, #arrow_right:active {
				bottom:28px;
			}
		</style>
    
   
     <script src="asset/js/bootstrap.js" type="text/javascript"></script>
      <script src="asset/js/jquery.js"></script>    
        <link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="lib/maximage/js/jquery.cycle.all.min.js" type="text/javascript"></script>
        <script src="lib/maximage/js/jquery.easing.min.js" type="text/javascript"></script>
        <script src="lib/maximage/js/jquery.maximage.min.js" type="text/javascript"></script>
   <script type="text/javascript">
			$(function(){
				if(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())){
						localStorage.setItem("page", "album");
				}
			
					
				$('#maximage').maximage({
					cycleOptions: {
						fx: 'fade',
						speed: 3000, // Has to match the speed for CSS transitions in jQuery.maximage.css (lines 30 - 33)
						timeout: 500,
						prev: '#arrow_left',
						next: '#arrow_right',
						
					},
					cssBackgroundSize: false,
					backgroundSize: 'auto'
				});
				
			
		
			});
		</script>
     
</head>
<body>
	<a href="" id="arrow_left"><img src="asset/img/arrow_left.png" alt="Slide Left" /></a>
<div id="maximage">
<?php
    if(!empty($data1))
             {
                             
            	foreach($data1['data'] as $photo)
                  {
                    echo "<img src='{$photo->source}' />";
              }
             }
?>

</div>
       
		<a href="" id="arrow_right"><img src="asset/img/arrow_right.png" alt="Slide Right" /></a>
</body>
  </html>
 	