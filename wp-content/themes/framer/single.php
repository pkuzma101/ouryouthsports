<?php
/**
 * The single post template file.
 *
 * @package framer
 */

get_header('inside');
?>
<div class="insideposts">

<div class="wrapper">
<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'inc/partials/content', 'single' );
				endwhile;
			?>
<?php the_post_navigation(); ?>
</div>
</div>
<?php get_footer();