<?php
/**
 * Loop Template Name: 媒体内容
 *
 */
?>

<div class="media">

	<a class="media-cap" target="_blank" href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'thumbnail' );
		}
		?>
	</a>

	<div class="media-body">
		<div class="media-body-title">
			<a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
		<p><?php echo mb_strimwidth( strip_tags( apply_filters( 'the_content', $post->post_content ) ), 0, 120, "……" ); ?></p>
	</div>

</div>