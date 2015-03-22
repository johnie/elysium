<?php
/*
* Plugin Name: Plugin Name
* Plugin URI: https://github.com/johnie/plugin-boilerplate
* Description:
* Version: 0.0.1
* Author: Johnie Hjelm
* Author URI: http://johnie.se
* License: MIT
*/

/*
Copyright 2015 Johnie Hjelm <johniehjelm@me.com> (http://johnie.se)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Elysium' ) ) {

  class Elysium {

    private static $instance;

    /**
     * Tag identifier used by file includes and selector attributes.
     * @var string
     */

    public $tag;

    /**
     * User friendly name used to identify the plugin.
     * @var string
     */

    public $name;

    /**
     * Description of the plugin.
     * @var string
     */

    public $description;

    /**
     * Current version of the plugin.
     * @var string
     */

    public $version;

    /**
     * Plugin loader instance.
     *
     * @since 1.0.0
     *
     * @return object
     */

    public static function instance() {
      if ( ! isset( self::$instance ) ) {
        self::$instance = new static;
        self::$instance->setup_globals();
        self::$instance->setup_actions();
      }

      return self::$instance;
    }

    /**
     * Initiate the plugin by setting the default values and assigning any
     * required actions and filters.
     *
     * @access private
     */

    private function setup_actions() {

    	if ( is_admin() ):
    		add_filter( 'enter_title_here', array( $this, 'change_elysium_title' ) );
				add_action( 'admin_enqueue_scripts', 'register_admin_scripts' );
    	endif;

			add_action( 'wp_enqueue_scripts', 'register_elysium_assets', 30 );
      add_action( 'init', array( $this, '_elysium_post_type'), 0 );

    }

    /**
 		 * Initiate the globals
 		 *
     * @access private
 		 */

    private function setup_globals() {
      $this->tag = 'elysium';
      $this->name = 'Elysium';
      $this->description = 'Simple Membership plugin';
      $this->version = '1.0.0';
    }


    /**
     * Register the elysium post type for members
 		 */

 		function _elysium_post_type() {
 			register_post_type( 'elysium', array(
        'labels' => array(
          'name'                => __( 'Medlemmar', 'elysium' ),
          'singular_name'       => __( 'Medlem', 'elysium' ),
          'menu_name'           => __( 'Medlemmar', 'elysium' ),
          'new_item'            => __( 'Lägg till medlem', 'elysium' ),
          'add_new'             => __( 'Lägg till medlem', 'elysium' ),
          'add_new_item'        => __( 'Lägg till medlem', 'elysium' ),
          'not_found'           => __( 'Vi hittade ingen medlem', 'elysium' ),
          'not_found_in_trash'  => __( 'Vi hittade ingen medlem i soptunnan', 'elysium' )
        ),
        'public'              => false,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'menu_icon'   				=> 'dashicons-id',
        'rewrite'             => array('slug' => 'medlemmar'),
        'supports'            => array( 'title' ),
        'capability_type'     => 'post'
      ) );
 		}

  }

  /**
	 * Change elysium post type title placeholder
	 */

	function change_elysium_title( $title ) {
		$screen = get_current_screen();

		if ( 'elysium' == $screen->post_type ) {
			$title = __('För- och efternamn', 'elysium');
		}

		return $title;
	}

	/**
	 * Elysium assets
	 */

	function register_elysium_assets() {
		wp_enqueue_script( 'elysium_js', home_url() . '/lib/elysium/assets/js/elysium.js', array(), null );
	}

	/**
	 * Admin scripts
	 */

	function register_admin_scripts() {
		wp_enqueue_script( 'lockdown_js', home_url() . '/lib/elysium/assets/js/lockdown.js', array(), null );
	}

}

if ( !function_exists( 'elysium' ) ) {
  function elysium() {
    return Elysium::instance();
  }
}

add_action( 'plugins_loaded', 'elysium' );
