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
$helper = new FacebookRedirectLoginHelper('http://fbphotoalbum-rtcampapp.rhcloud.com/userhome.php';);
try {
    $session = $helper->getSessionFromRedirect();
} catch (Exception $ex) {

}
if (isset($_SESSION['token'])) {
    $session = new FacebookSession($_SESSION['token']);
    try {
        $session->Validate('1441679196091390', '3ffd92469c7d6e15dc86db6a695fee95');
       // $session->Validate('333190363521048', '64e0596fa72ef6478cfa6d3a8e3a484e');
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
    if(isset($response))
    {
        $user_profile_graphObject = $response->getGraphObject();
        $_SESSION['userid']=$user_profile_graphObject->getProperty('id');
        $profile_picture = new FacebookRequest($session, 'GET', '/me/picture/', array('redirect' => false, 'height' => '200', 'type' => 'normal', 'width' => '200',));
        $response = $profile_picture->execute();
        $profile_picture_graphObject = $response->getGraphObject();
        $url = $profile_picture_graphObject->getProperty('url');
    }
    $albumrequest = new FacebookRequest($session, 'GET', '/me/albums');
    $response = $albumrequest->execute();
    $graphObject = $response->getGraphObject();
    $albums = $graphObject->asArray('data');
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="asset/js/jquery.js"></script>    
        <link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="asset/css/FbAlbum.css" rel="stylesheet" type="text/css"/>
        <title>Facebook Photo Album</title>
        <script>
            $(document).ready(function() {
                 var Selected = new Array();
                $("#downloadS").click(function() {
                    $('#waitModal').modal('show');
                    $("input:checkbox:checked").map(function() {
                        Selected.push(this.value);
                    });
                    post_data();
                });
                $("#downloadAll").click(function() {
                    $('#waitModal').modal('show');
                    $("input:checkbox").map(function() {
                        Selected.push(this.value);
                    });
                    post_data();
                });
                 $(".caption button").click(function() {
                      $('#waitModal').modal('show');
                            Selected.push($(this).val());
                            post_data();
                 });
                function post_data() {
                        $.post("Download.php", {"Selected": Selected}, function(response) {        
                        if (response != "fail" && response.split(".")[1] == "zip")
                        {
                            $("#download").attr("href",response);
                            $('#waitModal').modal('hide');
                            $('#downloadModal').modal('show');
                            $("#download").click(function() {
                                         $('#downloadModal').modal('hide');
                                  });
                        }
                        else 
                        {
                             alert(response);
                            //alert("Opps, something wasn't right, Try again");
                        }
                    });
    }
            });
        </script>
    </head>
    <body style="background-image: url('asset/img/furley_bg.png')">
        <form>
            <div>
                <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Facebook Photo Album</a>
                        </div>
                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="#" id="downloadS" >Download Selected</a></li>
                                <li><a href="#" id="downloadAll">Download All</a></li> 
                                <li class="active"><a href="./">Log Out</a></li>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container main">
                <div class="profile">
                    
                    <div class="profile2"></div>
                    <div class="profileImage"><?php  echo "<img class='img-circle' src='" . $url . "' width=225 height=225 /><br/>"; ?><p><?php if(isset($user_profile_graphObject)){echo $user_profile_graphObject->getProperty('name'); } ?></p></div>
                </div>
                <div class="block">
                    <p class="block-heading">Albums</p>
                    <div class="block-body gallery">
                        <div class="row">
                            <?php 
                                if (!empty($albums)) 
                        {
                            foreach ($albums["data"] as $album) {
                                    $getPhotos_request = new FacebookRequest( $session, 'GET', '/' . $album->id . '/photos');
                                    $getPhotos_response = $getPhotos_request->execute();
                                    $getPhotos_graphObject = $getPhotos_response->getGraphObject();
                                    $photos = array();
                                    $photos = $getPhotos_graphObject->asArray('data');                      
                             
                                         if (!empty($photos)) 
                                         {
                                             ?>
                                                <div class="col-sm-6 col-md-3">
                                                <div class="thumbnail">
                                    <?php
                                                 $photo = $photos['data'][0];
                                                 echo "<a href='albumSlideShow.php?albumId=". $album->id."'><img src='{$photo->source}' style='min-height:200px;height:200px;width:100%;' /></a>";
                                         
//                                         else
//                                         {
//                                             echo  "<img src='asset/img/empty_album.png' style='min-height:200px;height:200px;width:100%;'/>";
//                                         }
                                    ?>
                                                    
                                </div>
                                <div class="caption">
                                    <h4><?php echo $album->name ?></h4>
                                    <p>
                                        <?php echo "<input type='checkbox' name='cb' value='" . $album->id . "'/>  ";?>
                                        <button type="button" id="single" value="<?php echo $album->id ?>" class="btn btn-primary">Download This  Album</button>
                                        </p>
                                </div> </div> <?php } ?>
                           
                        <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
        <div class="modal fade" id="waitModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
             <img src="asset/img/progress.GIF" alt=""/>  <h3 style="float:left;">Your Album being prepared.........</h3>
            </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div>
        <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
             <h3>Your Album is ready to download....Click to Download.....</h3> <a href="#" id="download" class="btn btn-primary" role="button">Download</a>
            </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div>
        
        <script src="asset/js/bootstrap.js" type="text/javascript"></script>
        
    </body>
</html>
























<!--<html>
    <head>
        <meta charset="utf-8" />
        <script src="asset/js/jquery.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $("#downloadS").click(function() {
                    var Selected = new Array();
                    $("input:checked").each(function() {
                        Selected.push(this.value);
                    });
                    $.post("Download.php", {"Selected": Selected}, function(response) {        
                        if (response != "fail" && response.split(".")[1] == "zip")
                        {
                            location.href = response;
                        }
                        else 
                        {
                            alert("Opps, something wasn't right, Try again");
                        }
                    });
                });
            });
        </script>
        <title>Facebook Photo Album</title>
    </head>
    <body>
        <form action="Download.php" method="get">
            <input type='button' id='downloadS' value='Download Selected'/>
             get album photos 
             $request1 = new FacebookRequest(
              $session,
              'GET',
              '/'.$obj -> id.'/photos'
            );
            $response1 = $request1->execute();
            $graphObject1 = $response1->getGraphObject();
            $data1 = array();
            $data1 = $graphObject1 -> asArray('data');
                    
                    if(!empty($data1))
                    {
                            foreach($data1['data'] as $photo)
                    {
                        echo "<img src='{$photo->source}' /><br/>";
                    }
            <!-- }  


             
            
            // $request1 = new FacebookRequest(
            // $session,
            // 'GET',
            // '/{.$obj -> id.}/picture'	
            // );
            // $response1 = $request1->execute();
            // $graphObject1 = $response1->getGraphObject();
            // $data1 = array();
            // $data1 = $graphObject1 -> asArray('data');
            
            // if(!empty($data1))
            // {
                            // var_dump($data1);
            //		foreach($data1['data'] as $photo)
            //      {
            //        echo "<img src='{$photo->source}' /><br/>";
            //  }
            // }

            <input type="submit" value="Submit" />
        </form>
    </body>
</html>-->
