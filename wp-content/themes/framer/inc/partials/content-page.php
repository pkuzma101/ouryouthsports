<?php
/**
 * @package framer
 */
?>
<div class="row">
		<div class="col-1-1"><div class="wrap-col test postcontent">
        
    <?php the_post_thumbnail( 'framer-full' ); ?>
        <div id="content"> <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


<h1 class="postcontenttitle"><?php the_title() ?></h1>
<div class="authormeta"><?php esc_html_e( 'By :', 'framer' ); ?> <?php the_author_posts_link(); ?> </div>
        
<?php
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'framer' ),
			'after'  => '</div>',
		) );
		
		edit_post_link( esc_html__( 'Edit', 'framer' ), '<span class="edit-link">', '</span>' );
		?>
        <br>
</div></div>
<?php comments_template(); ?>
</div>

</div>
	


</div>

