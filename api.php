<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Save plugin options on post.
if ( elysium_is_method( 'post' ) ) {
	_elysium_save_plugin_options();
}


/**
 * Get plugin options prefix.
 *
 * @return string
 */

function _elysium_get_plugin_options_prefix() {
	return 'elysium_plugin_option_';
}


/**
 * Render the plugin options view.
 */

function _elysium_render_plugin_options() {
	include_once dirname( __FILE__ ) . '/views/plugin-options.php';
}


/**
 * Save plugin options data.
 */

function _elysium_save_plugin_options() {
	$pattern = '/^' . str_replace( '_', '\_', _elysium_get_plugin_options_prefix() ) . '.*/';
	$data    = array();
	$keys    = preg_grep( $pattern, array_keys( $_POST ) );

	foreach ( $keys as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		// Fix for input fields that should be true or false.
		if ( $_POST[ $key ] === 'on' ) {
			$data[ $key ] = true;
		} else {
			$data[ $key ] = elysium_remove_trailing_quotes( $_POST[ $key ] );
		}
	}

	foreach ( $data as $key => $value ) {
		add_option( $key, $value );
	}
}


/**
 * Get plugin option value.
 *
 * @param string $key
 * @param mixed $default
 *
 * @return mixed
 */

function elysium_get_plugin_option( $key, $default ='' ) {
	$prefix = _elysium_get_plugin_options_prefix();
	return get_option( $prefix . $key, $default );
}


/**
 * Update plugin option.
 *
 * @param string $key
 * @param mixed $value
 */

function elysium_update_plugin_option( $key, $value ) {
	$prefix = _elysium_get_plugin_options_prefix();
	update_option( $prefix . $key, $value );
}
