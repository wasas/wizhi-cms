<?php

add_action( 'init', 'cms_settings_page' );

function cms_settings_page() {

	if ( function_exists( 'fm_register_submenu_page' ) ) {
		fm_register_submenu_page( 'wizhi_cms_settings', 'options-general.php', __( 'CMS Settings', 'wizhi' ) );
	}

	add_action( 'fm_submenu_wizhi_cms_settings', function () {

		$post_types = wizhi_post_types();

		$fm = new Fieldmanager_Group( [
			'name'     => 'wizhi_cms_settings',
			'children' => [
				"enabled_post_types" => new Fieldmanager_Checkboxes( __( 'Enabled content types', 'wizhi' ), [ 'options' => $post_types ] ),
				"is_enable_css"      => new Fieldmanager_Checkbox( __( 'Use build-in CSS', 'wizhi' ) ),
				"is_enable_js"       => new Fieldmanager_Checkbox( __( 'Use build-in Javascript', 'wizhi' ) ),
				"is_enable_font"     => new Fieldmanager_Checkbox( __( 'Load build-in FontAwesome icons', 'wizhi' ) ),
				"is_enable_builder"  => new Fieldmanager_Checkbox( __( 'Enable Shortcode UI', 'wizhi' ) ),
			],
		] );

		$fm->activate_submenu_page();

	} );

}