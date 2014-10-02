<?php

use Facebook\FacebookSession;

class FacebookPhotoAlbumTest extends PHPUnit_Framework_TestCase {

	private static $accesstoken = 'CAACZBvaCZAk9gBAAJsoZBjHAm6f3oV2zutjh96lEBYygPnqfGdNSs6pKCvUOlhio6OGyNzFT3fJIsZBPKpgC7NZC7SfrchMEoPKxwVah6NYU7ZCxgN3ZBztoVdoOxkGaU7ZC5D6ZCZBaK0cGkZCOfy4Wmr0ioaTAWdTyeVK8SaWC4HhDWyyCiILofHOlZCiBOwCNFVoqFn2YZBlri53pWHObszjOl';
	protected $object;

	protected function setUp() {
		$this->object          = new FacebookPhotoAlbum();
		$this->object->session = new FacebookSession( self::$accesstoken );
	}

	protected function tearDown() {
		unset( $this->object );
	}

	public function test_login_url() {

		$test_login_url = $this->object->login_url();
		$this->assertNotEmpty( $test_login_url );
	}

	public function test_logout_url() {
		$test_logout_url = $this->object->logout_url();
		$this->assertNotEmpty( $test_logout_url );
	}

	public function test_get_user_info() {
		// Remove the following lines when you implement this test.
		$testArray = $this->object->get_user_info();
		//$this -> assertArrayHasKey( 'userid' , $testArray );
		$this->assertNotEmpty( $testArray );
		$this->assertCount( 1, $testArray );
		foreach ( $testArray as $values ) {
			$this->assertArrayHasKey( 'userid', $values );
			$this->assertArrayHasKey( 'username', $values );
		}
		$this->assertEquals( 'Nikunj Vagadiya', $testArray[0]['username'] );

	}

	public function test_get_user_profile_picture() {
		$testurl = $this->object->get_user_profile_picture();
		$this->assertNotEmpty( $testurl );
		$this->assertEquals( 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/c46.46.572.572/s200x200/3498_439459886116353_502076372_n.jpg?oh=c996e33200dacf69391036ef87613045&oe=54CC5E5A&__gda__=1422109585_43906044640e4be66fead779cbc519e0', $testurl );
	}

	public function test_get_user_album() {
		$testalbum = $this->object->get_user_album();
		$this->assertNotEmpty( $testalbum );
		$this->assertGreaterThan( 0, count( $testalbum ) );
		foreach ( $testalbum as $values ) {
			$this->assertArrayHasKey( 'albumId', $values );
			$this->assertArrayHasKey( 'albumName', $values );
			$this->assertArrayHasKey( 'albumCover', $values );
		}
	}

	public function test_make_zip() {
		$albumid[0] = '106444206084591';
		$userid     = '100001570371568';
		$testzip    = $this->object->make_zip( $albumid, $userid );
		$this->assertNotEmpty( $testzip );
		$this->assertContains( 'zip', $testzip );
	}

	public function test_get_album_photos() {
		$albumid    = '106444206084591';
		$testphotos = $this->object->get_album_photos( $albumid );
		$this->assertNotEmpty( $testphotos );
		$this->assertGreaterThan( 0, count( $testphotos ) );

	}
}
