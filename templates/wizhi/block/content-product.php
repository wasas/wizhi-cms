<?php
/**
 * Loop Template Name: 产品系列
 *
 */
?>

<div class="pure-g row">
    <div class="pure-u-1 pure-u-md-1-4">

    </div>
    <div class="pure-u-1 pure-u-md-3-4">
		<?php
		$args = [
			'post_type'      => 'post',
			'posts_per_page' => 4,
		];

		$wp_query = new WP_Query( $args );
		?>


        <div class="pure-g row">
			<?php while ( have_posts() ) : the_post(); ?>
                <div class="pure-u-1-2 pure-u-md-1-2">
					<?php if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'md' );
					} ?>
                </div>
			<?php endwhile; ?>
        </div>
    </div>
</div>

