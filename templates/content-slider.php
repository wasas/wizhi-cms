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


<li class="bx-item">
	<a target="_blank" href="<?php echo $cus_links; ?> " title="<?php the_title(); ?>">
		<?php if ( has_post_thumbnail() ) the_post_thumbnail('full'); ?>
	</a>
</li>