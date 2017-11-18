<?php
/**
 * The template part for displaying latest news section on the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package framer
 */

?>

<div id="blogposts">
<div class="wrapper">

<div class="row home-posts">
<?php

while ( have_posts() ) :
the_post();
?>

<div class="col-1-4">
      <div class="wrap-col">
      
     <div class="blogpost"> 
      <a href="<?php the_permalink(); ?>">
      <div class="blogimageoverlay">
       <h3 class="blogposttitle"><?php the_title(); ?></h3>
      </div></a>
     <?php if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'framer-blog-thumb', array( 'class' => 'framer-featured-image' ) );
								} else { ?>
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/default.gif" alt="<?php the_title_attribute() ?>" />
						<?php } ?>
      </div><!-- Blogpost -->
       
</div><!-- end wrap-col -->      
</div><!-- end col-1-4 -->

<?php endwhile; ?>
</div><!-- end row -->

<?php the_posts_pagination(); ?>

</div><!-- End Wrapper -->


</div><!-- End blogposts -->
