<?php

$feat_image_url = '';
if ( has_post_thumbnail() ) {
	$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
}

?>

<li>
	<a href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'slider' );
		} ?>
	</a>
</li>
