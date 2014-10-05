<?php
// Autoload the required files
require_once 'lib/sdk/autoload.php';

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookAuthorizationException;

class FacebookPhotoAlbum {

	var $app_id;
	var $app_secret;
	var $redirectUrl;
	var $helper;
	var $session;
	var $permissions;
	var $logoutUrl;

	public function __construct() {
		$this->permissions = array( 'user_photos' );
		$this->app_id      = '209721652515800';
		$this->app_secret  = '6d4dc86a9b34d4a9ef23299b47ef24b1';
		$this->redirectUrl = 'http://fbphotoalbum-rtcampapp.rhcloud.com/userhome.php';
		$this->logoutUrl   = 'http://fbphotoalbum-rtcampapp.rhcloud.com/logout.php';
		// Initialize the SDK
		FacebookSession::setDefaultApplication( $this->app_id, $this->app_secret );

		// Create the login helper with REDIRECT_URL

		$this->helper = new FacebookRedirectLoginHelper( $this->redirectUrl );

		if ( isset( $_SESSION['token'] ) ) {
			$this->session = new FacebookSession( $_SESSION['token'] );
			try {
				//validate session
				if ( ! $this->session->Validate( $this->app_id, $this->app_secret ) ) {
					$this->session = null;
				}
			} catch ( FacebookAuthorizationException $e ) {
				$this->session = null;
			}
		} else {
			//When No session exists
			try {
				$this->session = $this->helper->getSessionFromRedirect();
			} catch ( FacebookRequestException $ex ) {
				// When Facebook returns an error
			} catch ( Exception $ex ) {
				// When validation fails or other local issues
			}

		}
		if ( isset( $this->session ) ) {
			//to store token into session variable
			$_SESSION['token'] = $this->session->getToken();
		}
	}

	/**
	 * Returns the login URL.
	 */
	public function login_url() {
		return $this->helper->getLoginUrl( $this->permissions );
	}

	/**
	 * Returns the logout URL.
	 */

	public function logout_url() {
		return $this->helper->getLogoutUrl( $this->session, $this->logoutUrl );
	}

	/**
	 * Returns the current user's info as an array.
	 */
	public function get_user_info() {

		if ( $this->session ) {
			/**
			 * Retrieve User’s Profile Information
			 */
			// Graph API to request user data
			$user_profile             = new FacebookRequest( $this->session, 'GET', '/me' );
			$profile_response         = $user_profile->execute();
			$user_profile_graphObject = $profile_response->getGraphObject();
			//store userid and username
			$userid      = $user_profile_graphObject->getProperty( 'id' );
			$username    = $user_profile_graphObject->getProperty( 'name' );
			$userinfo[0] = array( 'userid' => $userid, 'username' => $username );

			// Return array with userinfo
			return $userinfo;
		}

		return false;
	}

	public function get_user_profile_picture() {
		if ( $this->session ) {

			//Make API requests to get user profile picture
			$profile_picture             = new FacebookRequest( $this->session, 'GET', '/me/picture/', array(
				'redirect' => false,
				'height'   => '200',
				'type'     => 'normal',
				'width'    => '200',
			) );
			$picture_response            = $profile_picture->execute();
			$profile_picture_graphObject = $picture_response->getGraphObject();
			// store user profile picture url in to variable
			$url = $profile_picture_graphObject->getProperty( 'url' );

			// Returns url
			return $url;
		}

		return false;
	}

	public function get_user_album() {
		if ( $this->session ) {
			//make facebook album request
			$album_request     = new FacebookRequest( $this->session, 'GET', '/me/albums' );
			$album_response    = $album_request->execute();
			$album_graphObject = $album_response->getGraphObject();
			//Array of albums
			$albums        = $album_graphObject->asArray( 'data' );
			$albums_detail = array();

			if ( ! empty( $albums ) ) {
				//Loop through all albums
				foreach ( $albums['data'] as $album ) {
					//Make request for get All photos into indi
					$getPhotos_request     = new FacebookRequest( $this->session, 'GET', '/' . $album->id . '/photos' );
					$getPhotos_response    = $getPhotos_request->execute();
					$getPhotos_graphObject = $getPhotos_response->getGraphObject();
					$photos                = $getPhotos_graphObject->asArray( 'data' );
					//Check album contain photos or not
					if ( ! empty( $photos ) ) {

						//Retrive first element of array
						$photo                       = $photos['data'][0];
						$albums_detail[ $album->id ] = array(
							'albumId'    => $album->id,
							'albumName'  => $album->name,
							'albumCover' => $photo->source
						);
					}
				}
			}

			// Get response as an array with album_details
			return $albums_detail;
		}

		return false;
	}

	public function make_zip( $arr_albumId, $userid ) {
		if ( $this->session ) {

			try {
				$fname = $userid . '.zip';
				$zip   = new ZipArchive;
				$zip->open( $fname, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE );
				//Array of id of album that user requested for download
				//Loop through all album id which user requested for
				foreach ( $arr_albumId as $albumId ) {
					//Make facebook request for get all photos within album
					$getPhotoRequest  = new FacebookRequest( $this->session, 'GET', '/' . $albumId . '/photos' );
					$getPhotoresponse = $getPhotoRequest->execute();
					$photo_graphObj   = $getPhotoresponse->getGraphObject();
					$photolist        = $photo_graphObj->asArray( 'data' );
					//Check album contain photos inside or not
					if ( ! empty( $photolist ) ) {
						//Loop throughout all photos inside album
						foreach ( $photolist['data'] as $photo ) {
							//Add photo of album in to zip file
							$zip->addFromString( $albumId . '/' . basename( $photo->source ), file_get_contents( $photo->source ) );
						}
					}
				}
				//Close zip file
				$zip->close();

				//Return name of zip file for download
				return $fname;

			} catch ( Exception $ex ) {
				return 'failed';
			}
		}

		return false;
	}

	public function get_album_photos( $albumId ) {
		if ( $this->session ) {

			$getphoto_request  = new FacebookRequest( $this->session, 'GET', '/' . $albumId . '/photos' );
			$photo_response    = $getphoto_request->execute();
			$photo_graphObject = $photo_response->getGraphObject();
			//Array of photos inside album
			$photos = $photo_graphObject->asArray( 'data' );

			// Get response as an array of photos which returned by API request
			return $photos;
		}
	}
}

