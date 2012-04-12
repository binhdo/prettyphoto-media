<?php
/*
 * prettyPhoto Media plugin settings page
 */

add_action( 'admin_menu', 'prettyphoto_settings_page_setup' );

function prettyphoto_settings_page_setup() {
	global $prettyphotomedia;

	$prettyphotomedia -> scriptlocations = array(
		'header' => 'place script in header',
		'footer' => 'place script in footer',
		'none' => 'do not load script'
	);
	$prettyphotomedia -> themes = array(
		'pp_default' => 'Default theme',
		'light_rounded' => 'Light rounded theme',
		'dark_rounded' => 'Dark rounded semi-transparent theme',
		'light_square' => 'Light square theme',
		'dark_square' => 'Dark square semi-transparent theme',
		'facebook' => 'Facebook inspired theme'
	);
	$prettyphotomedia -> animation_speeds = array(
		'fast' => __( 'fast' ),
		'slow' => __( 'slow' ),
		'normal' => __( 'normal' )
	);
	$prettyphotomedia -> wmodes = array(
		'window' => __( 'window' ),
		'opaque' => __( 'opaque' ),
		'transparent' => __( 'transparent' ),
		'direct' => __( 'direct' ),
		'gpu' => __( 'gpu' )
	);

	add_action( 'admin_init', 'prettyphoto_register_settings' );

	$prettyphotomedia -> settings_page = add_options_page( __( 'prettyPhoto Media Settings' ), __( 'prettyPhoto Media' ), 'manage_options', 'prettyphoto-settings-page', 'prettyphoto_display_settings_page' );

	add_action( 'load-' . $prettyphotomedia -> settings_page, 'prettyphoto_settings_sections' );

	/* Admin scripts & Styles */
	add_action( 'admin_print_scripts-' . $prettyphotomedia -> settings_page, 'prettyphoto_admin_scripts' );
	add_action( 'admin_print_styles-' . $prettyphotomedia -> settings_page, 'prettyphoto_admin_styles' );
}

function prettyphoto_register_settings() {
	register_setting( 'prettyphoto_settings', 'prettyphoto_settings', 'prettyphoto_validate_settings' );
}

function prettyphoto_settings_sections() {
	/* add_settings_section( $id, $title, $callback, $page ); */
	add_settings_section( 'main_settings', false, 'prettyphoto_section_main', 'prettyphoto-settings-page' );
	add_settings_section( 'custom_settings', false, 'prettyphoto_section_customisations', 'prettyphoto-settings-page' );
}

function prettyphoto_section_main() {
	global $prettyphotomedia;

	$html = '<div id="prettyphoto-media-section-main">' . "\n";

	$html .= prettyphoto_create_setting( array(
		'id' => 'loadppcss',
		'type' => 'checkbox',
		'label' => 'Load stylesheet',
		'desc' => __( 'load prettyPhoto.css' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'scriptlocation',
		'type' => 'select',
		'label' => 'Script location',
		'desc' => __( 'where to place the required javascript' ),
		'options' => $prettyphotomedia -> scriptlocations
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'ppselector',
		'type' => 'text',
		'label' => 'prettyPhoto selector',
		'desc' => __( '[default: prettyPhoto]' ),
		'class' => 'regular-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'hook',
		'type' => 'text',
		'label' => 'prettyPhoto hook',
		'desc' => __( 'the attribute tag to use for prettyPhoto hooks. For HTML5, use "data-rel" or similar. [default: rel]' ),
		'class' => 'regular-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'wpautogallery',
		'type' => 'checkbox',
		'label' => 'WP galleries',
		'desc' => __( 'utilise prettyPhoto to display WordPress image galleries by default' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'theme',
		'type' => 'select',
		'label' => 'Theme',
		'desc' => __( 'light_rounded / dark_rounded / light_square / dark_square / facebook [default: pp_default]' ),
		'options' => $prettyphotomedia -> themes
	) );

	$html .= '</div>' . "\n";

	echo $html;
}

function prettyphoto_section_customisations() {
	global $prettyphotomedia;

	$html = '<div id="prettyphoto-media-section-customisations">' . "\n";

	$html .= prettyphoto_create_setting( array(
		'id' => 'animation_speed',
		'type' => 'select',
		'label' => 'Animation speed',
		'desc' => __( 'fast / slow / normal [default: fast]' ),
		'options' => $prettyphotomedia -> animation_speeds
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'slideshow',
		'type' => 'text',
		'label' => 'Slideshow',
		'desc' => __( 'false OR interval time in ms  [default: 5000]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'autoplay_slideshow',
		'type' => 'checkbox',
		'label' => 'Autoplay slideshow',
		'desc' => __( 'true / false [default: false]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'opacity',
		'type' => 'text',
		'label' => 'Opacity',
		'desc' => __( 'Value between 0 and 1  [default: 0.8]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'show_title',
		'type' => 'checkbox',
		'label' => 'Show title',
		'desc' => __( 'true / false [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'allow_resize',
		'type' => 'checkbox',
		'label' => 'Allow resize',
		'desc' => __( 'Resize the photos bigger than viewport. true / false [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'allow_expand',
		'type' => 'checkbox',
		'label' => 'Allow expand',
		'desc' => __( 'Allow the user to expand a resized image. true / false [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'default_width',
		'type' => 'text',
		'label' => 'Default width',
		'desc' => __( '[default: 500]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'default_height',
		'type' => 'text',
		'label' => 'Default height',
		'desc' => __( '[default: 344]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'counter_separator_label',
		'type' => 'text',
		'label' => 'Counter separator label',
		'desc' => __( 'The separator for the gallery counter 1 "of" 2 [default: /]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'horizontal_padding',
		'type' => 'text',
		'label' => 'Horizontal padding',
		'desc' => __( 'The padding on each side of the picture [default: 20]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'hideflash',
		'type' => 'checkbox',
		'label' => 'Hide Flash',
		'desc' => __( 'Hides all the flash objects on a page, set to TRUE if flash appears over prettyPhoto [default: false]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'wmode',
		'type' => 'select',
		'label' => 'wmode',
		'desc' => __( 'Set the flash wmode attribute [default: opaque]' ),
		'options' => $prettyphotomedia -> wmodes
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'autoplay',
		'type' => 'checkbox',
		'label' => 'Autoplay',
		'desc' => __( 'Automatically start videos: true / false [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'modal',
		'type' => 'checkbox',
		'label' => 'Modal',
		'desc' => __( 'If set to true, only the close button will close the window [default: false]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'deeplinking',
		'type' => 'checkbox',
		'label' => 'Deeplinking',
		'desc' => __( 'Allow prettyPhoto to update the url to enable deeplinking. [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'overlay_gallery',
		'type' => 'checkbox',
		'label' => 'Overlay gallery',
		'desc' => __( 'If set to true, a gallery will overlay the fullscreen image on mouse over [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'overlay_gallery_max',
		'type' => 'text',
		'label' => 'Overlay gallery max',
		'desc' => __( 'Maximum number of pictures in the overlay gallery [default: 30]' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'keyboard_shortcuts',
		'type' => 'checkbox',
		'label' => 'Keyboard shortcuts',
		'desc' => __( 'Set to false if you open forms inside prettyPhoto [default: true]' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'ie6_fallback',
		'type' => 'checkbox',
		'label' => 'IE6 fallback',
		'desc' => __( 'compatibility fallback for IE6 [default: true]' ),
		'value' => true
	) );

	$html .= '</div>' . "\n";

	echo $html;
}

function prettyphoto_create_setting($args = array(), $before = '<div class="row">', $after = '</div>') {

	extract( $args );

	$field_value = prettyphoto_get_option( $id );
	$prefix = 'prettyphoto_settings';

	$html = $before . "\n";

	switch ($type) {
		case 'text':
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<input type="text" id="' . $id . '" name="' . $prefix . '[' . $id . ']" ';
			if ( isset( $class ) )
				$html .= 'class="' . $class . '" ';
			$html .= 'value="' . esc_attr( $field_value ) . '" />' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		case 'checkbox':
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<input type="checkbox" id="' . $id . '" name="' . $prefix . '[' . $id . ']" value="' . $value . '"' . checked( $value, $field_value, false ) . ' />' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		case 'select':
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<select id="' . $id . '" name="' . $prefix . '[' . $id . ']">' . "\n";
			foreach ( $options as $option => $label ) {
				$html .= "\t\t" . '<option value="' . esc_attr( $option ) . '"' . selected( $option, $field_value, false ) . '>';
				$html .= esc_attr( $label );
				$html .= '</option>' . "\n";
			}
			$html .= "\t" . '</select>' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		default:
			break;
	}

	$html .= $after . "\n";

	return $html;

}

function prettyphoto_display_settings_page() {
	echo '<div class="wrap prettyphoto-media-settings">' . "\n";
	
	screen_icon( );
	
	echo '<h2>' . __( 'prettyPhoto Media Settings' ) . '</h2>' . "\n";
	
	settings_errors( );
	
	echo '<form method="post" action="options.php">' . "\n";
	
	settings_fields( 'prettyphoto_settings' );
	
	echo '<div class="ui-tabs">' . "\n";
	echo '<h2 class="nav-tab-wrapper">' . "\n";
	echo '<ul class="ui-tabs-nav">' . "\n";
	echo '<li><a href="#prettyphoto-media-section-main" class="nav-tab">Main Settings</a></li>' . "\n";
	echo '<li><a href="#prettyphoto-media-section-customisations" class="nav-tab">prettyPhoto Customisation</a></li>' . "\n";
	echo '</ul>' . "\n";
	echo '</h2>' . "\n";
	
	do_settings_sections( $_GET['page'] );
	
	submit_button( esc_attr__( 'Update Settings' ) );
	
	echo '</div>';
	
	echo '</form>' . "\n";
	echo '</div>' . "\n";
}

function prettyphoto_validate_settings($input) {
	global $prettyphotomedia;

	if ( array_key_exists( $input['scriptlocation'], $prettyphotomedia -> scriptlocations ) )
		$settings['scriptlocation'] = $input['scriptlocation'];

	if ( array_key_exists( $input['theme'], $prettyphotomedia -> themes ) )
		$settings['theme'] = $input['theme'];

	if ( array_key_exists( $input['animation_speed'], $prettyphotomedia -> animation_speeds ) )
		$settings['animation_speed'] = $input['animation_speed'];

	if ( array_key_exists( $input['wmode'], $prettyphotomedia -> wmodes ) )
		$settings['wmode'] = $input['wmode'];

	$settings['counter_separator_label'] = esc_attr( $input['counter_separator_label'] );

	$text_options = array(
		'ppselector',
		'hook'
	);

	foreach ( $text_options as $text_option ) {
		$settings[$text_option] = trim( esc_attr( $input[$text_option] ) );
	}

	$int_options = array(
		'padding',
		'slideshow',
		'default_width',
		'default_height',
		'horizontal_padding',
		'overlay_gallery_max'
	);

	foreach ( $int_options as $int_option ) {
		$settings[$int_option] = is_numeric( $input[$int_option] ) ? intval( $input[$int_option] ) : prettyphoto_get_option( $int_option );
	}

	$float_options = array( 'opacity' );

	foreach ( $float_options as $float_option ) {
		$settings[$float_option] = is_numeric( $input[$float_option] ) ? round( $input[$float_option], 2 ) : prettyphoto_get_option( $float_option );
	}

	$checkbox_options = array(
		'loadppcss',
		'wpautogallery',
		'autoplay_slideshow',
		'show_title',
		'allow_resize',
		'allow_expand',
		'autoplay',
		'modal',
		'deeplinking',
		'overlay_gallery',
		'keyboard_shortcuts',
		'ie6_fallback'
	);

	foreach ( $checkbox_options as $checkbox_option ) {
		$settings[$checkbox_option] = isset( $input[$checkbox_option] ) ? true : false;
	}

	return $settings;
}

function prettyphoto_admin_scripts() {
	wp_print_scripts( 'jquery-ui-tabs' );
	wp_enqueue_script( 'prettyphoto-media-admin-script', PRETTYPHOTO_URI . 'js/admin-script.js' );
}

function prettyphoto_admin_styles() {
	wp_enqueue_style( 'prettyphoto-media-admin-style', PRETTYPHOTO_URI . 'css/admin-style.css' );
}
?>