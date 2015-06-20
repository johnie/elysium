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
    		add_action( 'admin_init', array( $this, '_elysium_meta_data' ) );

    		add_filter( 'manage_edit-elysium_columns', array( $this, 'add_table_columns' ) );
    		add_action( 'manage_elysium_posts_custom_column', array( $this, 'output_table_columns_data'), 10, 2 );
    		add_filter( 'manage_edit-elysium_sortable_columns', array( $this, 'define_sortable_table_columns') );
    		add_filter( 'request', array( $this, 'orderby_sortable_table_columns' ) );

    		add_filter( 'enter_title_here', array( $this, 'change_elysium_title' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

				add_action('admin_menu', array( $this, 'elysium_settings_page' ) );
    	endif;

			add_action( 'wp_dashboard_setup', array( $this, 'elysium_dashboard' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_elysium_assets' ) );

			add_action( 'wp_ajax_elysium_kruger', array( $this, 'elysium_ajax_with_kruger' ) );
			add_action( 'wp_ajax_nopriv_elysium_kruger', array( $this, 'elysium_ajax_with_kruger' ) );

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
        'public'              => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'menu_icon'   				=> 'dashicons-id',
        'rewrite'             => array('slug' => 'medlemmar'),
        'supports'            => array( 'title' ),
        'capability_type'     => 'post'
      ) );
  	}


		/**
 		 * Markup for meta boxes
 		 */

		function _elysium_meta_data() {
			add_meta_box( 'elysium_type',
				'Medlem Information',
				array( $this, 'display_elysium_type' ),
				'elysium', 'normal', 'high'
			);
		}


  	/**
 		 * Markup for meta boxes
 		 */

 		function display_elysium_type( $post ) {
 			$values 		= get_post_custom( $post->ID );
 			$personnr 	= $this->elysium_decrypt(get_post_meta( $post->ID, '_elysium_personnr', true ));
 			$gatuadress = get_post_meta( $post->ID, '_elysium_gatuadress', true );
 			$stad 		  = get_post_meta( $post->ID, '_elysium_stad', true );
 			$postnr 	  = get_post_meta( $post->ID, '_elysium_postnr', true );
 			$region 	  = get_post_meta( $post->ID, '_elysium_region', true );
 			$telefon	  = get_post_meta( $post->ID, '_elysium_telefon', true );
 			$epost  	  = get_post_meta( $post->ID, '_elysium_epost', true );
 			$dhr 			  = get_post_meta( $post->ID, '_elysium_dhr_medlem', true );
 			wp_nonce_field( 'elysium_meta_box_nonce', '_elysium_nonce' );
 			?>
				<div id="medlem-data">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									Personnummer
								</th>
								<td>
									<input type="text" name="elysium_personnr" value="<?php echo $personnr; ?>" placeholder="ÅÅMMDD-1234" disabled>
								</td>
							</tr>
							<tr>
								<th scope="row">
									Kontakt
								</th>
								<td>
									<label for="elysium_telefon">telefon</label><br>
									<input type="text" name="elysium_telefon" value="<?php echo $telefon; ?>" placeholder="070-999 13 37">
								</td>
								<td>
									<label for="elysium_epost">E-post</label><br>
									<input type="text" name="elysium_epost" value="<?php echo $epost; ?>" placeholder="namn@gmail.com">
								</td>
							</tr>
							<tr>
								<th scope="row">
									Adress
								</th>
								<td>
									<label for="elysium_gatuadress">Gatuadress</label><br>
									<input type="text" name="elysium_gatuadress" value="<?php echo $gatuadress; ?>" placeholder="Storforsplan 44">
								</td>
								<td>
									<label for="elysium_stad">Stad</label><br>
									<input type="text" name="elysium_stad" value="<?php echo $stad; ?>" placeholder="Farsta">
								</td>
								<td>
									<label for="elysium_postnr">Postnr</label><br>
									<input type="text" name="elysium_postnr" value="<?php echo $postnr; ?>" placeholder="12347">
								</td>
							</tr>
							<tr>
								<th scope="row">
									Region
								</th>
								<td>
									<input type="text" name="elysium_region" value="<?php echo $region; ?>" placeholder="Stockholms Län">
								</td>
							</tr>
							<tr>
								<th scope="row">
									Medlem i DHR?
								</th>
								<td>
									<input type="hidden" name="elysium_dhr_medlem" value="<?php echo $dhr; ?>" />
									<input type="checkbox" name="elysium_dhr_medlem" <?php if( $dhr == 'on' ) { ?>checked="checked"<?php } ?> />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
 			<?php
 		}


 		/**
 		 * Save meta box data
 		 */

 		function save_elysium_data( $post_id ) {
 			// Bail if we're doing an auto save
      if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
      // if our nonce isn't there, or we can't verify it, bail
      if( !isset( $_POST['_elysium_nonce'] ) || !wp_verify_nonce( $_POST['_elysium_nonce'], 'elysium_meta_box_nonce' ) ) return;

      if( isset( $_POST['elysium_gatuadress'] ) ) {
        update_post_meta( $post_id, '_elysium_gatuadress', esc_attr( $_POST['elysium_gatuadress'] ) );
      }
      if( isset( $_POST['elysium_stad'] ) ) {
        update_post_meta( $post_id, '_elysium_stad', esc_attr( $_POST['elysium_stad'] ) );
      }
      if( isset( $_POST['elysium_postnr'] ) ) {
        update_post_meta( $post_id, '_elysium_postnr', esc_attr( $_POST['elysium_postnr'] ) );

      	$json = file_get_contents('http://yourmoneyisnowmymoney.com/api/zipcodes/?zipcode='. preg_replace('/\s+/','', $_POST['elysium_postnr']) );
			  $obj = json_decode($json);
			  $address = $obj->results[0]->address;
			  $lan = explode(', ', $address);

	      update_post_meta( $mid, '_elysium_region', esc_attr( $lan[1] ) );
      }
      if( isset( $_POST['elysium_telefon'] ) ) {
        update_post_meta( $post_id, '_elysium_telefon', esc_attr( $_POST['elysium_telefon'] ) );
      }
      if( isset( $_POST['elysium_epost'] ) ) {
        update_post_meta( $post_id, '_elysium_epost', esc_attr( $_POST['elysium_epost'] ) );
      }
      if( isset( $_POST['elysium_dhr_medlem'] ) ) {
        update_post_meta( $post_id, '_elysium_dhr_medlem', esc_attr( $_POST['elysium_dhr_medlem'] ) );
      }
 		}


 		/**
		 * Encrypt string
		 *
		 * @param string $value
		 *
		 * @return string
		 */

		public function elysium_encrypt( $value ) {
			$key = hash( 'SHA256', ELYSIUM_CRYPT_SALT . ELYSIUM_CRYPT_UNCLOKER, true );

		 	srand();

			$iv = mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC ), MCRYPT_RAND );

			if ( strlen( $iv_base64 = rtrim( base64_encode( $iv ), '=' ) ) != 22 ) {
				return false;
			}

			$encrypted = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $value. md5( $value ), MCRYPT_MODE_CBC, $iv ) );

			return $iv_base64 . $encrypted;
		}


		/**
		 * Decrypt string.
		 *
		 * @param string $value
		 *
		 * @return string
		 */

		function elysium_decrypt( $value ) {
			$key    	 = hash( 'SHA256', ELYSIUM_CRYPT_SALT . ELYSIUM_CRYPT_UNCLOKER, true );
		 	$iv 			 = base64_decode( substr( $value, 0, 22 ) . '==' );
		 	$value     = substr( $value, 22 );
		 	$decrypted = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $key, base64_decode( $value ), MCRYPT_MODE_CBC, $iv ), "\0\4" );
		 	$hash 		 = substr( $decrypted, -32 );
		 	$decrypted = substr( $decrypted, 0, -32 );

			if ( md5( $decrypted ) != $hash ) {
				return false;
			}

			return $decrypted;
		}


 		/**
 		 * Add table columns for elysium
 		 */

 		function add_table_columns( $columns ) {
 			$columns['_elysium_epost'] = __( 'E-post', 'elysium' );
 			$columns['_elysium_telefon'] = __( 'telefon', 'elysium' );
 			$columns['_elysium_gatuadress'] = __( 'Adress', 'elysium' );

 			return $columns;
 		}


 		/**
 		 * Outputs data from medlem
 		 */

 		function output_table_columns_data( $columnName, $post_id ) {
    	$field = get_post_meta( $post_id, $columnName, true );

    	if ( '_elysium_gatuadress' == $columnName ) {
    		echo get_post_meta( $post_id, '_elysium_gatuadress', true ) . ', ' . get_post_meta( $post_id, '_elysium_postnr', true ) . ' ' . get_post_meta( $post_id, '_elysium_stad', true );
    	} else {
    		echo $field;
    	}
		}


 		/**
 		 * Sortable columns
 		 */

 		function define_sortable_table_columns( $columns ) {
	    $columns['_elysium_epost'] = '_elysium_epost';
	    $columns['_elysium_telefon'] = '_elysium_telefon';

	    return $columns;
		}


 		/**
 		 * Orderby request columns
 		 */

		function orderby_sortable_table_columns( $vars ) {
	    // Don't do anything if we are not on the Contact Custom Post Type
	    if ( 'elysium' != $vars['post_type'] ) return $vars;

	    // Don't do anything if no orderby parameter is set
	    if ( ! isset( $vars['orderby'] ) ) return $vars;

	    // Check if the orderby parameter matches one of our sortable columns
	    if ( $vars['orderby'] == '_elysium_epost' OR
        $vars['orderby'] == '_elysium_telefon' ) {
        // Add orderby meta_value and meta_key parameters to the query
        $vars = array_merge( $vars, array(
            'meta_key' => $vars['orderby'],
            'orderby' => 'meta_value',
        ));
	    }

	    return $vars;
		}


	  /**
		 * Change elysium post type title placeholder
		 */

		function change_elysium_title( $title ) {
			$screen = get_current_screen();

			if ( 'elysium' == $screen->post_type ) {
				return $title = __('För- och efternamn', 'elysium');
			}

			return $title;
		}

		/**
		 * Elysium assets
		 */

		function register_elysium_assets() {
			wp_enqueue_script( 'validation', '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js', array(), null, false );
			wp_enqueue_script( 'elysium_js', home_url() . '/lib/elysium/assets/js/elysium.js', array(), null, true );

			$ajax_args = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
    		'nonce'   => wp_create_nonce( 'elysium_ajax_nonce' )
			);
			wp_localize_script( 'elysium_js', 'kruger', $ajax_args, true );
		}

		/**
		 * Admin scripts
		 */

		function register_admin_scripts() {
			wp_enqueue_script( 'lockdown_js', home_url() . '/lib/elysium/assets/js/lockdown.js', array(), null );
		}


		/**
		 * Elysium ajax requests
		 */

		function elysium_ajax_with_kruger() {
			global $wpdb;
			$personnr = $wpdb->get_col( $wpdb->prepare( "
			  SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
			  LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			  WHERE pm.meta_key = '%s'
			  AND p.post_status = '%s'
			  AND p.post_type = '%s'
			  ", '_elysium_personnr', 'private', 'elysium' ) );

			function personnrExist($param, $arr) {
			  return isset( $_POST[$param] ) && in_array($_POST[$param], $arr);
			}

			if ( empty( $_POST['personnr'] ) || empty( $_POST['nonce'] ) ) die('empty');
		  // Verify the nonce
		  if (! wp_verify_nonce( $_POST['nonce'], 'elysium_ajax_nonce' ) ) die('bad nonce bad');

		  if ( class_exists('Personnummer') ) {
		  	$validpnr = \Personnummer::valid($_POST['personnr']);
		  } else {
		  	$validpnr = 'Validation for Personnumer doesn\'t exist. Check https://github.com/frozzare/php-is-personnummer';
		  }

			echo json_encode(array(
				'valid_pnr' => $validpnr,
			  'pnr_exist' => personnrExist('personnr', $personnr),
			  'input' => $_POST['personnr']
			));

			die;
		}


	  /**
	   * Markup for elysium
	   */

	  public function render() {
	    include_once dirname( __FILE__ ) . '/inc/form.php';
	  }

	  /**
	   * Markup for front elysium
	   */

	  public function front_render() {
	    include_once dirname( __FILE__ ) . '/inc/front_form.php';
	  }


	  /**
 		 * Return if personnr exist
 		 * @return boolean
 		 */

 		public function personnr_exist($param) {
 			global $wpdb;
	    $personnr = $wpdb->get_col( $wpdb->prepare( "
	      SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
	      LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	      WHERE pm.meta_key = '%s'
	      AND p.post_status = '%s'
	      AND p.post_type = '%s'
	      ", '_elysium_personnr', 'private', 'elysium' ) );

	    if ( isset( $_POST[$param] ) ) {
	    	return !in_array($_POST[$param], $personnr);
	    }
 		}


 		/**
 	   * Count members
 		 */

 		public function count_members() {
 			$membersObj = wp_count_posts($this->tag);
 			$total = $membersObj->draft + $membersObj->private;

 			return $total;
 		}


 		/**
     * Send mail when creating a member
 		 */

 		public function send_mail($fornamn, $efternamn, $email) {
 			$to 		 = elysium_get_plugin_option( 'admin_email' );
 			$subject = __("En ny medlem!", "elysium");
 			$headers = 'From: '.get_option( 'blogname' ).' <'.get_option( 'admin_email' ).'>' . "\r\n";
 			$template = '<table>
 				<tbody>
 					<tr>
 						<td>'.$fornamn.' '.$efternamn.' - '.$email.'</td>
 					</tr>
 				</tbody>
 			</table>';

 			wp_mail( $to, $subject, $template, $headers);
 		}


 		/**
 		 * Add settings page
 		 */

 		function elysium_settings_page() {
			add_submenu_page('options-general.php',
      'Medlemsregister', 'Medlemsregister', 'manage_options',
      'elysium', array( $this, 'elysium_settings') );
 		}


 		/**
 		 * Render settings page
 		 */

 		function elysium_settings() {
			include_once dirname( __FILE__ ) . '/views/plugin-options.php';
 		}


 		/**
     * Add dasboard widget
 		 */

 		function elysium_dashboard() {
 			wp_add_dashboard_widget(
 				'elysium-dashboard-widget',
 				'Medlemsregister',
 				array( $this, 'elysium_dashboard_content' ),
 				$controll_callback = null
 			);
 		}


 		/**
     * Elysium dashboard widget content
		 */

		function elysium_dashboard_content() {
			echo '<h3>Antal medlemmar just nu: ' . $this->count_members() . '</h3>';
		}

	}

}


/**
 * API
 */

include_once dirname( __FILE__ ) . '/api.php';


/**
 * Function for elysium
 */
function elysium_render() {
  elysium()->render();
}

/**
 * Function for front elysium
 */
function elysium_front_render() {
  elysium()->front_render();
}


if ( !function_exists( 'elysium' ) ) {
  function elysium() {
    return Elysium::instance();
  }
}

add_action( 'plugins_loaded', 'elysium' );
