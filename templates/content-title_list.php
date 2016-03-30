<?php
/**
 * Template part for displaying posts.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rhine
 */

?>

<?php
$cus_links = get_post_meta( get_the_ID(), 'cus_links', true );
if ( empty( $cus_links ) ) {
	$cus_links = get_permalink();
}
?>

<li class="ui-list-item">
	<span class="pull-right time"><?php the_time( 'm-d' ); ?></span>
	<a href="<?php echo $cus_links; ?>" title="<?php the_title(); ?>">
		<?php echo wp_trim_words( get_the_title(), 100, "..." ); ?>
	</a>
</li>