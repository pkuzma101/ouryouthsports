<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package framer
 */
get_header();
?>

<div class="insideposts">

	<div class="wrapper">
		<?php
		get_template_part( 'inc/partials/content', '404' );
		?>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer();