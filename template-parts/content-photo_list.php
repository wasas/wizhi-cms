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

<div class="media">

	<a class="media-cap" target="_blank" href="<?php echo $cus_links; ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		}
		?>
	</a>

	<div class="media-body">
		<div class="media-body-title">
			<a class="media-cap" target="_blank" href="<?php echo $cus_links; ?>">
				<?php echo wp_trim_words( get_the_title(), 100, "..." ); ?>
			</a>
			<p><?php echo wp_trim_words( $post->post_content, $content, "..." ); ?> </p>
		</div>
	</div>

</div>