<?php
/*
 * prettyPhoto Media plugin settings page
 */

add_action( 'admin_menu', 'prettyphoto_settings_page_setup' );

function prettyphoto_settings_page_setup() {
	global $prettyphotomedia;

	$prettyphotomedia -> scriptlocations = array(
		'header' => __( 'place script in header', 'prettyphoto-media' ),
		'footer' => __( 'place script in footer', 'prettyphoto-media' ),
		'none' => __( 'do not load script', 'prettyphoto-media' )
	);
	$prettyphotomedia -> themes = array(
		'pp_default' => __( 'Default theme', 'prettyphoto-media' ),
		'light_rounded' => __( 'Light rounded theme', 'prettyphoto-media' ),
		'dark_rounded' => __( 'Dark rounded semi-transparent theme', 'prettyphoto-media' ),
		'light_square' => __( 'Light square theme', 'prettyphoto-media' ),
		'dark_square' => __( 'Dark square semi-transparent theme', 'prettyphoto-media' ),
		'facebook' => __( 'Facebook inspired theme', 'prettyphoto-media' )
	);
	$prettyphotomedia -> animation_speeds = array(
		'fast' => __( 'fast', 'prettyphoto-media' ),
		'slow' => __( 'slow', 'prettyphoto-media' ),
		'normal' => __( 'normal', 'prettyphoto-media' )
	);
	$prettyphotomedia -> wmodes = array(
		'window' => __( 'window', 'prettyphoto-media' ),
		'opaque' => __( 'opaque', 'prettyphoto-media' ),
		'transparent' => __( 'transparent', 'prettyphoto-media' ),
		'direct' => __( 'direct', 'prettyphoto-media' ),
		'gpu' => __( 'gpu', 'prettyphoto-media' )
	);

	add_action( 'admin_init', 'prettyphoto_register_settings' );

	$prettyphotomedia -> settings_page = add_options_page( __( 'prettyPhoto Media Settings', 'prettyphoto-media' ), __( 'prettyPhoto Media', 'prettyphoto-media' ), 'manage_options', 'prettyphoto-settings-page', 'prettyphoto_display_settings_page' );

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
		'label' => __( 'Load stylesheet', 'prettyphoto-media' ),
		'desc' => __( 'load prettyPhoto.css', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'scriptlocation',
		'type' => 'select',
		'label' => __( 'Script location', 'prettyphoto-media' ),
		'desc' => __( 'where to place the required javascript', 'prettyphoto-media' ),
		'options' => $prettyphotomedia -> scriptlocations
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'ppselector',
		'type' => 'text',
		'label' => __( 'prettyPhoto selector', 'prettyphoto-media' ),
		'desc' => __( '[default: prettyPhoto]', 'prettyphoto-media' ),
		'class' => 'regular-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'hook',
		'type' => 'text',
		'label' => __( 'prettyPhoto hook', 'prettyphoto-media' ),
		'desc' => __( 'the attribute tag to use for prettyPhoto hooks. For HTML5, use "data-rel" or similar. [default: rel]', 'prettyphoto-media' ),
		'class' => 'regular-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'wpautogallery',
		'type' => 'checkbox',
		'label' => __( 'WP galleries', 'prettyphoto-media' ),
		'desc' => __( 'utilise prettyPhoto to display WordPress image galleries by default', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'show_twitter',
		'type' => 'checkbox',
		'label' => __( 'Show Twitter', 'prettyphoto-media' ),
		'desc' => __( 'display the Twitter "Share a link" button', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'id' => 'show_facebook',
		'type' => 'checkbox',
		'label' => __( 'Show Facebook', 'prettyphoto-media' ),
		'desc' => __( 'display the Facebook "Like" button', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'theme',
		'type' => 'select',
		'label' => __( 'Theme', 'prettyphoto-media' ),
		'desc' => __( 'light_rounded / dark_rounded / light_square / dark_square / facebook [default: pp_default]', 'prettyphoto-media' ),
		'options' => $prettyphotomedia -> themes
	) );

	$html .= '</div>' . "\n";

	echo $html;
}

function prettyphoto_section_customisations() {
	global $prettyphotomedia;

	$html = '<div id="prettyphoto-media-section-customisations">' . "\n";

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'animation_speed',
		'type' => 'select',
		'label' => __( 'Animation speed', 'prettyphoto-media' ),
		'desc' => __( 'fast / slow / normal [default: fast]', 'prettyphoto-media' ),
		'options' => $prettyphotomedia -> animation_speeds
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'slideshow',
		'type' => 'text',
		'label' => __( 'Slideshow', 'prettyphoto-media' ),
		'desc' => __( 'false OR interval time in ms [default: 5000]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'autoplay_slideshow',
		'type' => 'checkbox',
		'label' => __( 'Autoplay slideshow', 'prettyphoto-media' ),
		'desc' => __( 'true / false [default: false]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'opacity',
		'type' => 'text',
		'label' => __( 'Opacity', 'prettyphoto-media' ),
		'desc' => __( 'Value between 0 and 1 [default: 0.8]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'show_title',
		'type' => 'checkbox',
		'label' => __( 'Show title', 'prettyphoto-media' ),
		'desc' => __( 'true / false [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'allow_resize',
		'type' => 'checkbox',
		'label' => __( 'Allow resize', 'prettyphoto-media', 'prettyphoto-media' ),
		'desc' => __( 'Resize the photos bigger than viewport. true / false [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'allow_expand',
		'type' => 'checkbox',
		'label' => __( 'Allow expand', 'prettyphoto-media' ),
		'desc' => __( 'Allow the user to expand a resized image. true / false [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'default_width',
		'type' => 'text',
		'label' => __( 'Default width', 'prettyphoto-media' ),
		'desc' => __( '[default: 500]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'default_height',
		'type' => 'text',
		'label' => __( 'Default height', 'prettyphoto-media' ),
		'desc' => __( '[default: 344]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'counter_separator_label',
		'type' => 'text',
		'label' => __( 'Counter separator label', 'prettyphoto-media' ),
		'desc' => __( 'The separator for the gallery counter 1 "of" 2 [default: /]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'horizontal_padding',
		'type' => 'text',
		'label' => __( 'Horizontal padding', 'prettyphoto-media' ),
		'desc' => __( 'The padding on each side of the picture [default: 20]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'hideflash',
		'type' => 'checkbox',
		'label' => __( 'Hide Flash', 'prettyphoto-media' ),
		'desc' => __( 'Hides all the flash objects on a page, set to TRUE if flash appears over prettyPhoto [default: false]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'wmode',
		'type' => 'select',
		'label' => __( 'wmode', 'prettyphoto-media' ),
		'desc' => __( 'Set the flash wmode attribute [default: opaque]', 'prettyphoto-media' ),
		'options' => $prettyphotomedia -> wmodes
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'autoplay',
		'type' => 'checkbox',
		'label' => __( 'Autoplay', 'prettyphoto-media' ),
		'desc' => __( 'Automatically start videos: true / false [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'modal',
		'type' => 'checkbox',
		'label' => __( 'Modal', 'prettyphoto-media' ),
		'desc' => __( 'If set to true, only the close button will close the window [default: false]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'deeplinking',
		'type' => 'checkbox',
		'label' => __( 'Deeplinking', 'prettyphoto-media' ),
		'desc' => __( 'Allow prettyPhoto to update the url to enable deeplinking. [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'overlay_gallery',
		'type' => 'checkbox',
		'label' => __( 'Overlay gallery', 'prettyphoto-media' ),
		'desc' => __( 'If set to true, a gallery will overlay the fullscreen image on mouse over [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'overlay_gallery_max',
		'type' => 'text',
		'label' => __( 'Overlay gallery max', 'prettyphoto-media' ),
		'desc' => __( 'Maximum number of pictures in the overlay gallery [default: 30]', 'prettyphoto-media' ),
		'class' => 'small-text'
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'keyboard_shortcuts',
		'type' => 'checkbox',
		'label' => __( 'Keyboard shortcuts', 'prettyphoto-media' ),
		'desc' => __( 'Set to false if you open forms inside prettyPhoto [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= prettyphoto_create_setting( array(
		'groupid' => 'ppm_custom',
		'id' => 'ie6_fallback',
		'type' => 'checkbox',
		'label' => __( 'IE6 fallback', 'prettyphoto-media' ),
		'desc' => __( 'compatibility fallback for IE6 [default: true]', 'prettyphoto-media' ),
		'value' => true
	) );

	$html .= '</div>' . "\n";

	echo $html;
}

function prettyphoto_create_setting($args = array(), $before = '<div class="row">', $after = '</div>') {

	extract( $args );

	$settings_field = isset( $groupid ) ? prettyphoto_get_option( $groupid ) : prettyphoto_get_option( $id );
	$field_value = isset( $groupid ) ? $settings_field[$id] : $settings_field;
	$prefix = 'prettyphoto_settings';
	$setting_id = isset( $groupid ) ? $prefix . '[' . $groupid . '][' . $id . ']' : $prefix . '[' . $id . ']';

	$html = $before . "\n";

	switch ($type) {
		case 'text' :
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<input type="text" id="' . $id . '" name="' . $setting_id . '" ';
			if ( isset( $class ) )
				$html .= 'class="' . $class . '" ';
			$html .= 'value="' . esc_attr( $field_value ) . '" />' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		case 'checkbox' :
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<input type="checkbox" id="' . $id . '" name="' . $setting_id . '" value="' . $value . '"' . checked( $value, $field_value, false ) . ' />' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		case 'select' :
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<select id="' . $id . '" name="' . $setting_id . '">' . "\n";
			foreach ( $options as $option => $label ) {
				$html .= "\t\t" . '<option value="' . esc_attr( $option ) . '"' . selected( $option, $field_value, false ) . '>';
				$html .= esc_attr( $label );
				$html .= '</option>' . "\n";
			}
			$html .= "\t" . '</select>' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		case 'textarea' :
			if ( isset( $label ) )
				$html .= "\t" . '<label for="' . $id . '">' . $label . '</label>' . "\n";
			$html .= "\t" . '<textarea id="' . $id . '" name="' . $setting_id . '" ';
			if ( isset( $class ) )
				$html .= 'class="' . $class . '" ';
			if ( isset( $rows ) )
				$html .= 'rows="' . $rows . '"';
			if ( isset( $cols ) )
				$html .= 'cols="' . $cols . '"';
			$html .= '>' . esc_attr( $field_value ) . '</textarea>' . "\n";
			if ( isset( $desc ) )
				$html .= '<span class="description">' . esc_attr( $desc ) . '</span>' . "\n";
			break;

		default :
			break;
	}

	$html .= $after . "\n";

	return $html;

}

function prettyphoto_display_settings_page() {
	echo '<div class="wrap prettyphoto-media-settings">' . "\n";

	screen_icon( );

	echo '<h2>' . __( 'prettyPhoto Media Settings', 'prettyphoto-media' ) . '</h2>' . "\n";

	settings_errors( );

	echo '<form method="post" action="options.php">' . "\n";

	settings_fields( 'prettyphoto_settings' );

	echo '<div class="ui-tabs">' . "\n";
	echo '<h2 class="nav-tab-wrapper">' . "\n";
	echo '<ul class="ui-tabs-nav">' . "\n";
	echo '<li><a href="#prettyphoto-media-section-main" class="nav-tab">' . __( 'Main Settings', 'prettyphoto-media' ) . '</a></li>' . "\n";
	echo '<li><a href="#prettyphoto-media-section-customisations" class="nav-tab">' . __( 'prettyPhoto Customisation', 'prettyphoto-media' ) . '</a></li>' . "\n";
	echo '</ul>' . "\n";
	echo '</h2>' . "\n";

	do_settings_sections( $_GET['page'] );

	submit_button( esc_attr__( 'Update Settings', 'prettyphoto-media' ) );

	echo '</div>';

	echo '</form>' . "\n";
	echo '</div>' . "\n";
}

function prettyphoto_validate_settings($input) {
	global $prettyphotomedia;

	/* Main section */
	$main_checkbox_options = array(
		'loadppcss',
		'wpautogallery',
		'show_twitter',
		'show_facebook'
	);
	foreach ( $main_checkbox_options as $main_checkbox_option ) {
		$settings[$main_checkbox_option] = isset( $input[$main_checkbox_option] ) ? true : false;
	}

	if ( array_key_exists( $input['scriptlocation'], $prettyphotomedia -> scriptlocations ) )
		$settings['scriptlocation'] = $input['scriptlocation'];

	$main_text_options = array(
		'ppselector',
		'hook'
	);
	foreach ( $main_text_options as $main_text_option ) {
		$settings[$main_text_option] = trim( esc_attr( $input[$main_text_option] ) );
	}

	/* Customisation Section */
	if ( array_key_exists( $input['ppm_custom']['theme'], $prettyphotomedia -> themes ) )
		$settings['ppm_custom']['theme'] = $input['ppm_custom']['theme'];

	if ( array_key_exists( $input['ppm_custom']['animation_speed'], $prettyphotomedia -> animation_speeds ) )
		$settings['ppm_custom']['animation_speed'] = $input['ppm_custom']['animation_speed'];

	if ( array_key_exists( $input['ppm_custom']['wmode'], $prettyphotomedia -> wmodes ) )
		$settings['ppm_custom']['wmode'] = $input['ppm_custom']['wmode'];

	$settings['ppm_custom']['counter_separator_label'] = esc_attr( $input['ppm_custom']['counter_separator_label'] );

	$custom_int_options = array(
		'slideshow',
		'default_width',
		'default_height',
		'horizontal_padding',
		'overlay_gallery_max'
	);
	foreach ( $custom_int_options as $custom_int_option ) {
		if ( is_numeric( $input['ppm_custom'][$custom_int_option] ) )
			$settings['ppm_custom'][$custom_int_option] = intval( $input['ppm_custom'][$custom_int_option] );
	}

	if ( is_numeric( $input['ppm_custom']['opacity'] ) )
		$settings['ppm_custom']['opacity'] = round( $input['ppm_custom']['opacity'], 2 );

	$custom_checkbox_options = array(
		'autoplay_slideshow',
		'show_title',
		'allow_resize',
		'allow_expand',
		'hideflash',
		'autoplay',
		'modal',
		'deeplinking',
		'overlay_gallery',
		'keyboard_shortcuts',
		'ie6_fallback'
	);
	foreach ( $custom_checkbox_options as $custom_checkbox_option ) {
		$settings['ppm_custom'][$custom_checkbox_option] = isset( $input['ppm_custom'][$custom_checkbox_option] ) ? true : false;
	}

	/* social_tools settings */
	
	$twitter_markup = '<div class="twitter"><iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html?count=none&amp;url={location_href}" style="border:none; overflow:hidden; width:59px; height:20px;"></iframe></div>';
	
	$facebook_markup = '<div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?href={location_href}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:21px;" allowTransparency="true"></iframe></div>';
	
	if ( $settings['show_twitter'] && $settings['show_facebook'] ) {
		$settings['ppm_custom']['social_tools'] = $twitter_markup . $facebook_markup;
	} elseif ( $settings['show_twitter'] && ! $settings['show_facebook'] ) {
		$settings['ppm_custom']['social_tools'] = $twitter_markup;
	} elseif ( ! $settings['show_twitter'] && $settings['show_facebook'] ) {
		$settings['ppm_custom']['social_tools'] = $facebook_markup;
	} else {
		$settings['ppm_custom']['social_tools'] = false;
	}

	return $settings;
}

function prettyphoto_admin_scripts() {
	wp_print_scripts( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-cookie', PRETTYPHOTO_URI . 'js/jquery.cookie.js' );
	wp_enqueue_script( 'prettyphoto-media-admin-script', PRETTYPHOTO_URI . 'js/admin-script.js' );
}

function prettyphoto_admin_styles() {
	wp_enqueue_style( 'prettyphoto-media-admin-style', PRETTYPHOTO_URI . 'css/admin-style.css' );
}
?>