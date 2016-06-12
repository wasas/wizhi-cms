<?php
/**
 * Template part for displaying posts.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rhine
 */

?>

<li class="ui-list-item">
	<span class="pull-right time"><?php the_time( 'Y-m-d' ); ?></span>
	<a href="<?php the_permalink();; ?>" title="<?php the_title(); ?>">
		<?php echo wp_trim_words( get_the_title(), 100, "..." ); ?>
	</a>
</li>