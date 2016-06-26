<?php

/**
 * @return string attachment images src
 */
function wizhi_cms_banner_image() {

	global $post;

	if ( is_page() ) {

		$post_id = $post->ID;
		$subimg  = get_post_meta( $post_id, '_term_banner', true );

		if ( $subimg ) {
			$subimg = $subimg;
		} else {
			$post_id = $post->post_parent;
			$subimg  = get_post_meta( $post_id, '_term_banner', true );
		}

		$banner_src = get_term_meta( $term_id, '_term_banner', true );

	} elseif ( is_single() ) {

		//get post_type`s taxonomy
		$post_type  = $post->post_type;
		$taxonomies = array_values( get_object_taxonomies( $post_type, 'objects' ) ); #associated array to index array
		$taxonomy   = $taxonomies[ 0 ]->name;

		//get the first term
		$terms   = get_the_terms( $post->ID, $taxonomy );
		$term_id = $terms[ 0 ]->term_id;

		$banner_src = get_term_meta( $term_id, '_term_banner', true );

	} elseif ( is_tax() || is_category() ) {

		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;

		$banner_src = get_term_meta( $term_id, '_term_banner', true );

	} else {

		$banner_src = null;

	}

	if ( $banner_src ) {
		return $banner_src;
	} else {
		return '';
	}

}

?>
