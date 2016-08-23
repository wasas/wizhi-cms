<?php

add_action( 'after_setup_theme', 'wizhi_cms_term_meta' );
function wizhi_cms_term_meta() {

	$fm = new Fieldmanager_Textfield( [
		'name' => '_term_posts_per_page',
	] );
	$fm->add_term_meta_box( '每页显示的文章数量', 'category' );

	$fm = new Fieldmanager_Media( [
		'name' => '_banner_image',
	] );
	$fm->add_term_meta_box( '封面图像', 'category' );

	$fm = new Fieldmanager_Select( [
		'name'    => '_term_template',
		'options' => wizhi_get_loop_template( 'wizhi/category' ),
	] );
	$fm->add_term_meta_box( '存档模板', 'category' );
}