<?php

add_action( 'after_setup_theme', function () {
	$fm = new Fieldmanager_Textfield( [ 'name' => '_link_url', 'label' => __( 'Custom link', 'wizhi' ) ] );
	$fm->add_meta_box( __( 'Custom link', 'wizhi' ), 'slider' );
} );