<?php
/**
 * The template part for displaying latest news section on the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package framer
 */

$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
// WP_Query arguments
$args = array (
  'post_type'              => array( 'post' ),
  'post_status'            => array( 'publish' ),
  'paged'                  => $paged,
);

// The Query
$query_framer = new WP_Query( $args );

?>
<div id="blogposts">
<div class="wrapper">
<div class="row home-posts">
  
  <?php 

  if ( $query_framer->have_posts() ) {
    while ( $query_framer->have_posts() ) {
      $query_framer->the_post(); ?>
         

    <div class="col-1-4">
      <div class="wrap-col">
        <div class="blogpost">
          <a href="<?php the_permalink(); ?>">
            <div class="blogimageoverlay">
              <h3 class="blogposttitle"><?php the_title(); ?></h3>
            </div>
          </a>

          <?php
          if ( has_post_thumbnail() ) {
            the_post_thumbnail( 'framer-blog-thumb', array( 'class' => 'framer-featured-image' ) );
          }
          else { ?>
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/default.gif" alt="<?php the_title_attribute() ?>" />
          <?php } ?>
        </div><!-- Blogpost -->
      </div>
    </div>

    <?php } } ?>
</div>
<!-- end row -->

<?php wp_reset_postdata(); ?>

</div>
<!-- End Wrapper -->
</div>
<!-- End Wrapper -->
</div>
<!-- End blogposts -->
