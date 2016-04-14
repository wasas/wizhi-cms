<?php

$cus_links = get_post_meta( $post->ID, 'cus_links', true );
if ( empty( $cus_links ) ) {
	$cus_links = get_permalink();
}

$feat_image_url = '';
if ( has_post_thumbnail() ) {
	$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
}

?>


<li class="bx-item" style="background-size: contain; background-image:url(<?php echo $feat_image_url; ?> . ');">

	<a target="_blank" href="<?php echo $cus_links; ?> " title="<?php the_title(); ?>">

		<img src="<?php echo get_template_directory_uri(); ?>/front/dist/images/holder.png" alt="Slider Holder"/>

	</a>
</li>