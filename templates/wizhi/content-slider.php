<li>
	<a href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'slider' );
		} ?>
	</a>
</li>
