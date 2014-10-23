<?php

use Facebook\FacebookSession;

class FacebookPhotoAlbumTest extends PHPUnit_Framework_TestCase {

	// Access token  for manually creating session for testing 
	private static $accesstoken = 'CAACZBvaCZAk9gBAAJsoZBjHAm6f3oV2zutjh96lEBYygPnqfGdNSs6pKCvUOlhio6OGyNzFT3fJIsZBPKpgC7NZC7SfrchMEoPKxwVah6NYU7ZCxgN3ZBztoVdoOxkGaU7ZC5D6ZCZBaK0cGkZCOfy4Wmr0ioaTAWdTyeVK8SaWC4HhDWyyCiILofHOlZCiBOwCNFVoqFn2YZBlri53pWHObszjOl';
	protected $object;
	//Setup class object
	protected function setUp() {
		$this->object          = new FacebookPhotoAlbum();
		$this->object->session = new FacebookSession( self::$accesstoken );
	}
	//Destroy class object when test has finished
	protected function tearDown() {
		unset( $this->object );
	}

	//Ensure login_url not return empty
	public function test_login_url() {

		$test_login_url = $this->object->login_url();
		$this->assertNotEmpty( $test_login_url );
	}
	//Ensure logout_url not return empty
	public function test_logout_url() {
		$test_logout_url = $this->object->logout_url();
		$this->assertNotEmpty( $test_logout_url );
	}
	//Ensure get user info return array with key
	public function test_get_user_info() {
		
		$testArray = $this->object->get_user_info();
		$this->assertNotEmpty( $testArray );
		$this->assertCount( 1, $testArray );
		if ( is_array( $testArray ) ) {
		foreach ( $testArray as $values ) {
			$this->assertArrayHasKey( 'userid', $values );
			$this->assertArrayHasKey( 'username', $values );
		}
		
		$this->assertEquals( 'Nikunj Vagadiya', $testArray[0]['username'] );
		}
	}
	

	//Ensure get user album  return array of album
	public function test_get_user_album() {
		$testalbum = $this->object->get_user_album();
		$this->assertNotEmpty( $testalbum );
		$this->assertGreaterThan( 0, count( $testalbum ) );
		if ( is_array( $testalbum ) ) {
		foreach ( $testalbum as $values ) {
			$this->assertArrayHasKey( 'albumId', $values );
			$this->assertArrayHasKey( 'albumName', $values );
			$this->assertArrayHasKey( 'albumCover', $values );
		}
		}
	}
	//Ensure make zip return zip file name
	public function test_make_zip() {
		$album = array();
		$album[] = array( '106444206084591', 'Profile Pictures' );
		$userid     = '100001570371568';
		$testzip    = $this->object->make_zip( $album, $userid );
		$this->assertNotEmpty( $testzip );
		$this->assertContains( 'zip', $testzip );
	}
	//Ensure get album photos return array of photos
	public function test_get_album_photos() {
		$albumid    = '106444206084591';
		$testphotos = $this->object->get_album_photos( $albumid );
		$this->assertNotEmpty( $testphotos );
		$this->assertGreaterThan( 0, count( $testphotos ) );

	}
}
