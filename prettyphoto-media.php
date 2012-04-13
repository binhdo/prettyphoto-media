<?php
/*
 * Plugin Name: prettyPhoto Media
 * Plugin URI: http://binaryhideout.com
 * Description: Display images and other media utilising prettyPhoto.
 * Version: 1.1
 * Author: Markus Dobersalsky
 * Author URI: http://binaryhideout.com
 * License: GPLv2 or later
 */

$prettyphotomedia = new PrettyPhotoMedia();

class PrettyPhotoMedia {
	/* PHP5 constructor */
	function __construct() {
		add_action( 'plugins_loaded', array(
				&$this,
				'prettyphoto_init'
		) );
	}

	/* Initialise plugin */
	function prettyphoto_init() {
		/* Define constants */
		define( 'PRETTYPHOTO_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'PRETTYPHOTO_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'PRETTYPHOTO_VERSION', '1.1' );

		/* Load plugin functions */
		require_once (PRETTYPHOTO_DIR . 'prettyphoto-media_functions.php');

		/* Load settings page /*/
		if ( is_admin( ) ) {
			require_once (PRETTYPHOTO_DIR . 'prettyphoto-media_settings.php');
			add_filter( 'plugin_action_links', array(
					&$this,
					'prettyphoto_plugin_action_links'
			), 10, 2 );
			register_deactivation_hook( __FILE__, array(
					&$this,
					'prettyphoto_deactivate_plugin'
			) );
		}
	}

	function prettyphoto_plugin_action_links($links, $file) {
		if ( $file == plugin_basename( dirname( __FILE__ ) . '/prettyphoto-media.php' ) ) {
			$links[] = '<a href="options-general.php?page=prettyphoto-settings-page">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	function prettyphoto_deactivate_plugin() {
		delete_option( 'prettyphoto_settings' );
	}

}
?>
