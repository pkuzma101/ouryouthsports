<?php
/**
 * @package framer
 */
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