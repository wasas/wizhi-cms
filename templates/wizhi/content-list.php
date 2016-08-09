<?php
/**
 * Loop Template Name: 列表内容
 *
 */
?>

<li class="ui-list-item">
	<span class="pull-right time"><?php the_time( 'Y-m-d' ); ?></span>
	<a href="<?php the_permalink();; ?>" title="<?php the_title(); ?>">
		<?php echo wp_trim_words( get_the_title(), 100, "..." ); ?>
	</a>
</li>