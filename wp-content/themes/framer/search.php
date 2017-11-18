<?php
/**
 * The template for displaying search results pages.
 *
 * @package framer
 */

get_header('archives');
?>

<div id="blogposts">
<div class="wrapper">

<div class="row home-posts">


<?php
if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			get_template_part( 'inc/partials/content', 'search' );
		endwhile;
	else :
		get_template_part( 'inc/partials/content', 'none' );
	endif;
	?>


</div><!-- end row -->

 
 
<?php the_posts_pagination(); ?>

</div><!-- End Wrapper -->
</div><!-- End Wrapper -->


</div><!-- End blogposts -->



<?php get_footer();