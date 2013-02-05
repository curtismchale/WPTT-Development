<?php
// including our base plugin file
require_once( '../../wptt-dev.php' );

class WPTT_Dev_Tests extends WP_UnitTestCase{

	private $plugin;

	function setUp(){

		parent::setUp();
		$this->plugin = $GLOBALS['wptt-dev'];

		if( ! defined( 'WPTT_LOCAL' ) ) define( 'WPTT_LOCAL', 'http://local.wptt-logging.com' );
		if( ! defined( 'WPTT_DEV' ) ) define( 'WPTT_DEV', 'http://dev.wptt-logging.com' );
		if( ! defined( 'WPTT_LIVE' ) ) define( 'WPTT_LIVE', 'http://wptt-logging.com' );

	} // setUp()

	/**
	 * Makes sure that the plugin is ready by checking the $GLOBAL we set up
	 */
	function testPluginInitialization(){
		$this->assertFalse( null == $this->plugin );
	} // testPluginInitialization

	/**
	 * Tests to make sure that is_live() returns true when we are on the live env
	 *
	 * @test
	 */
	function test_live_is_true(){
		update_option( 'siteurl', 'http://wptt-logging.com' );
		$this->assertTrue( $this->plugin->is_live(), 'is_live() should be true' );
	}

	/**
	 * Tests to make sure that is_live() returns false when we are not on the live env
	 *
	 * @test
	 */
	function test_live_is_false(){
		update_option( 'siteurl', 'http://twitter.com' );
		$this->assertFalse( $this->plugin->is_live(), 'is_live() should be false' );
	}

	/**
	 * Tests to make sure that is_local() returns true when we are on the dev env
	 *
	 * @test
	 */
	function test_dev_is_true(){
		update_option( 'siteurl', 'http://dev.wptt-logging.com' );
		$this->assertTrue( $this->plugin->is_dev(), 'is_dev() should be true' );
	}

	/**
	 * Tests to make sure that is_local() returns false when we are not on the local env
	 *
	 * @test
	 */
	function test_dev_is_false(){
		update_option( 'siteurl', 'http://twitter.com' );
		$this->assertFalse( $this->plugin->is_dev(), 'is_dev() should be false' );
	}

	/**
	 * Tests to make sure that is_local() returns true when we are on the local env
	 *
	 * @test
	 */
	function test_local_is_true(){
		update_option( 'siteurl', 'http://local.wptt-logging.com' );
		$this->assertTrue( $this->plugin->is_local(), 'is_local() should be true' );
	}

	/**
	 * Tests to make sure that is_local() returns false when we are not on the local env
	 *
	 * @test
	 */
	function test_local_is_false(){
		update_option( 'siteurl', 'http://twitter.com' );
		$this->assertFalse( $this->plugin->is_local(), 'is_local() should be false' );
	}

} // WP_UnitTestCase

// this is a hack to restore testing. I really should look in to the run before/after stuff to save and reset this
update_option( 'siteurl', 'http://local.wptt-logging.com' );