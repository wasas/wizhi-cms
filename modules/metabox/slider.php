<?php

add_action( 'after_setup_theme', 'wizhi_cms_slider_meta' );
function wizhi_cms_slider_meta() {
	$fm = new Fieldmanager_Textfield( [ 'name' => '_link_url', 'label' => '自定义链接' ] );
	$fm->add_meta_box( '自定义链接', 'post' );
}