<?php
/**
 * Loop Template Name: Carousel 内容
 *
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package enter
 */

?>


<li>
	<div class="pic">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'carousel' );
			} ?>
		</a>
	</div>
	<div class="title">
		<a href="<?php the_permalink(); ?>"><?php echo mb_strimwidth( strip_tags( apply_filters( 'the_title', $post->post_title ) ), 0, 20, "..." ); ?>
		</a>
	</div>
</li>