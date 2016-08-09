<?php
/**
 * Loop Template Name: 新闻内容
 *
 */
?>

<li>
	<span class="pull-right"><?php the_time('Y-m-d'); ?></span>
	<a href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
	</a>
</li>
