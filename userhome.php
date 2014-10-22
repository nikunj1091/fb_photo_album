<?php
session_start();
require_once 'facebookphotoalbum.php';
//Create class Object
$userAlbum = new FacebookPhotoAlbum();
//Redirect user if not logged in
if ( ! isset( $userAlbum->session ) ) {
	header( 'Location: index.php' );
	}
//Get logout url
$logoutURL = $userAlbum->logout_url();
//Get user info
$userinfo  = $userAlbum->get_user_info();
//Get user profile picture url
$url       = $userAlbum->get_user_profile_picture();
//Get user album list
$albums    = $userAlbum->get_user_album();
//Store user id in session variable
if ( isset( $userinfo ) ) {
	$_SESSION['userid'] = $userinfo[0]['userid'];
}
?>
<html>
<head>
	<title>Facebook Photo Album</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="asset/js/jquery.js"></script>
	<link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css"/>
	<link href="asset/css/FbAlbum.css" rel="stylesheet" type="text/css"/>
	<title>Facebook Photo Album</title>
	<script>


		$(document).ready(function () {
			var Selected = new Array();

			$("#downloadS").click(function () {

				var checked = $("#frmAlbum input:checked").length > 0;
				if (!checked) {
					$('#alertModal').modal('show');

				}
				else {
					$('#waitModal').modal('show');
					$("input:checkbox:checked").map(function () {
						Selected.push(this.value);
					});
					post_data();
				}
			});
			$("#downloadAll").click(function () {
				$('#waitModal').modal('show');

				$("input:checkbox").map(function () {
					Selected.push(this.value);
				});
				post_data();
			});
			$(".caption button").click(function () {
				$('#waitModal').modal('show');

				Selected.push($(this).val());
				post_data();
			});
			function post_data() {
				$.post("download.php", {"Selected": Selected}, function (response) {
					if (response != "failed" && response.split(".")[1] == "zip") {
						$("#download").attr("href", response);
						$('#waitModal').modal('hide');
						$('#downloadModal').modal('show');
						$("#download").click(function () {
							$('#downloadModal').modal('hide');
						});
					}
					else {
						
						$('#waitModal').modal('hide');
						$('#errorModal').modal('show');

					}
				});
			}
		});
	</script>
</head>
<body style="background-image: url('asset/img/furley_bg.png')">
<form id="frmAlbum">
	<div>
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					        data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Facebook Photo Album</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#" id="downloadS">Download Selected</a></li>
						<li><a href="#" id="downloadAll">Download All</a></li>
<?php
						// Chheck logout url is set or not
if ( isset( $logoutURL ) ) {
							?>
							<li class="active"><?php echo '<a  href="' . $logoutURL . '">Logout</a>'; ?></li>
 <?php 
 } 
?>

					</ul>
				</div>
			</div>
		</div>

	</div>
	<div class="container main">
		<div class="profile">

			<div class="profile_bg1"></div>
			<div class="profileImage">
<?php 
if ( isset( $url ) ) {
					echo "<img class='img-circle' src='" . $url . "'  /><br/>";
} ?><p><?php if ( isset( $userinfo ) ) {
						echo $userinfo[0]['username'];
					} ?></p></div>
			<div class="profile_bg2"></div>
		</div>
		<div class="block">

			<div class="block-body gallery">
				<div class="row">
					<?php
					//Check album is empty or not
if ( ! empty( $albums ) ) {
	//Loop through all albums
 foreach ( $albums as $album ) {
							?>
							<div class="col-sm-6 col-md-3">
								<div class="thumbnail">
									<?php
									//Retrive first element of array

									echo "<a href='albumSlideShow.php?albumId=" . $album['albumId'] . "'><img src='{$album['albumCover']}' style='min-height:200px;height:200px;width:100%;' /></a>";
									?>

								</div>
								<div class="caption">
									<h4><?php echo $album['albumName'] ?></h4>

									<p>
										<?php echo "<input type='checkbox' name='cb' value='" . $album['albumId'] ."-".$album['albumName']. "'/>  "; ?>
										<button type="button" id="single" value="<?php echo $album['albumId']."-".$album['albumName']; ?>"
										        class="btn btn-primary">Download This Album
										</button>
									</p>
								</div>
							</div> <?php } ?>

					<?php } ?>
				</div>
			</div>
		</div>
	</div>

</form>
<div class="modal fade" data-backdrop="static"
     data-keyboard="false" id="waitModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="float:right;">Your Album is being prepared.........</h3><img src="asset/img/progress.GIF"
				                                                                        alt=""/>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="downloadModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Your Album is ready to download....Click to Download.....</h3> <a href="#" id="download"
				                                                                      class="btn btn-primary"
				                                                                      role="button">Download</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Please select atleast one album...........</h3>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Sorry Something went wrong because of slow network, please try again.......</h3>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<script src="asset/js/bootstrap.js" type="text/javascript"></script>
</body>
</html>
