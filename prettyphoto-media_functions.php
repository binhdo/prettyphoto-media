<?php
/*
 * prettyPhoto Media plugin functions
 */

add_action( 'init', 'prettyphoto_init_functions' );

function prettyphoto_init_functions() {
	$old_version = prettyphoto_get_option( 'version' );

	if ( get_option( 'prettyphoto_settings' === false ) ) {
		add_option( 'prettyphoto_settings', prettyphoto_get_default_settings( ) );
	} elseif ( $old_version !== PRETTYPHOTO_VERSION ) {
		prettyphoto_update_settings( );
	}

	if ( prettyphoto_get_option( 'loadppcss' ) && ! is_admin( ) ) {
		wp_enqueue_style( 'prettyphoto', PRETTYPHOTO_URI . 'css/prettyPhoto.css', false, '3.1.4', 'screen' );
	}

	switch ( prettyphoto_get_option( 'scriptlocation' ) ) {
		case 'header':
			wp_enqueue_script( 'prettyphoto', PRETTYPHOTO_URI . 'js/jquery.prettyPhoto-3.1.4.min.js', array( 'jquery' ), '3.1.4' );
			break;
		case 'footer':
			wp_enqueue_script( 'prettyphoto', PRETTYPHOTO_URI . 'js/jquery.prettyPhoto-3.1.4.min.js', array( 'jquery' ), '3.1.4', true );
			break;
		case 'none':
			break;
	}

	if ( prettyphoto_get_option( 'wpautogallery' ) ) {
		add_filter( 'wp_get_attachment_link', 'prettyphoto_get_attachment_link', 10, 4 );
	}

	add_action( 'wp_footer', 'prettyphoto_footer_script', 100 );
}

function prettyphoto_footer_script() {
	$ppm_defaults = array_splice( prettyphoto_get_default_settings( ), 6 );

	foreach ( $ppm_defaults as $key => $value ) {
		$option = prettyphoto_get_option( $key );
		if ( $option != $value ) {
			if ( is_bool( $option ) ) {
				$options_changed[] = ($option == 1) ? $key . ': ' . 'true' : $key . ': ' . 'false';
			} elseif ( is_numeric( $option ) ) {
				$options_changed[] = $key . ': ' . $option;
			} elseif ( is_string( $option ) ) {
				$options_changed[] = $key . ': ' . '\'' . $option . '\'';
			} else {
				$options_changed[] = $key . ': ' . $option;
			}
		}
	}

	$out = '<script>' . "\n";
	$out .= 'jQuery(function($) {' . "\n";

	$out .= '$(\'a[' . prettyphoto_get_option( 'hook' ) . '^="prettyPhoto"]\').prettyPhoto(';

	if ( isset( $options_changed ) ) {
		$out .= '{ ' . implode( ', ', $options_changed ) . ' }';
	}

	$out .= ');' . "\n";

	$out .= '});' . "\n";
	$out .= '</script>' . "\n";

	echo $out;

}

function prettyphoto_get_attachment_link($html, $id, $size, $permalink) {
	global $post;

	$pid = $post -> ID;
	$hook = prettyphoto_get_option( 'hook' );
	$selector = prettyphoto_get_option( 'ppselector' );

	if ( ! $permalink )
		$html = preg_match( '/' . $hook . '="/', $html ) ? str_replace( $hook . '="', $hook . '="' . $selector . '[gallery-' . $pid . '] ', $html ) : str_replace( '<a ', '<a ' . $hook . '="' . $selector . '[gallery-' . $pid . ']" ', $html );

	return $html;
	//return apply_filters( 'wp_get_attachment_link', "<a href='$url' title='$post_title'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}

function prettyphoto_get_option($option = '') {
	global $prettyphotomedia;

	if ( ! $option )
		return false;

	if ( ! isset( $prettyphotomedia -> settings ) )
		$prettyphotomedia -> settings = get_option( 'prettyphoto_settings' );

	if ( ! is_array( $prettyphotomedia -> settings ) || empty( $prettyphotomedia -> settings[$option] ) )
		return false;

	return $prettyphotomedia -> settings[$option];
}

function prettyphoto_update_settings() {
	/* Get the settings from the database. */
	$settings = get_option( 'prettyphoto_settings' );
	/* Get the default plugin settings. */
	$default_settings = prettyphoto_get_default_settings( );
	/* Loop through each of the default plugin settings. */
	foreach ( $default_settings as $setting_key => $setting_value ) {
		/* If the setting didn't previously exist, add the default value to the $settings array. */
		if ( ! isset( $settings[$setting_key] ) )
			$settings[$setting_key] = $setting_value;
	}
	$settings['version'] = PRETTYPHOTO_VERSION;
	/* Update the plugin settings. */
	update_option( 'prettyphoto_settings', $settings );
}

function prettyphoto_get_default_settings() {
	$settings = array(
		'version' => PRETTYPHOTO_VERSION,
		'loadppcss' => 1,
		'scriptlocation' => 'footer',
		'ppselector' => 'prettyPhoto',
		'hook' => 'rel',
		'wpautogallery' => 0,
		'theme' => 'pp_default',
		'animation_speed' => 'fast',
		'slideshow' => 5000,
		'autoplay_slideshow' => false,
		'opacity' => .8,
		'show_title' => true,
		'allow_resize' => true,
		'allow_expand' => true,
		'default_width' => 500,
		'default_height' => 344,
		'counter_separator_label' => '/',
		'horizontal_padding' => 20,
		'hideflash' => false,
		'wmode' => 'opaque',
		'autoplay' => true,
		'modal' => false,
		'deeplinking' => true,
		'overlay_gallery' => true,
		'overlay_gallery_max' => 30,
		'keyboard_shortcuts' => true,
		'ie6_fallback' => true
	);
	return $settings;
}
?>