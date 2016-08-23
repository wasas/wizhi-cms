<?php

add_action( 'after_setup_theme', 'wizhi_cms_post_meta' );
function wizhi_cms_post_meta() {
	$fm = new Fieldmanager_Media( [
		'name' => '_banner_image',
	] );
	$fm->add_meta_box( __( 'Cover image', 'wizhi' ), 'page' );
}