<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Illustratr
 */
?>
  <!-- Modal for Emailing -->
  <div id="email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Email this Contact</h4>
        </div>
        <div class="modal-body">
          <form id="email_form" method="post" role="form">
            <div class="form-group row">
              <label for="email_to">To: </label>
              <input type="text" class="form-control" id="email_to" name="email_to">
            </div>
            <div class="form-group row">
              <label for="subject">Subject: </label>
              <input type="text" class="form-control" id="email_subject" name="email_subject">
            </div>
            <div class="form-group row">
              <label for="subject">Message: </label>
              <textarea class="form-control" id="email_message" name="email_message" rows="8" cols="50"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info" id="send_email_btn" name="send_email_btn" style="float: left;">Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- email_modal -->

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-area clear">
			<?php
				if ( has_nav_menu( 'social' ) ) {
					wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'menu-social',
						'menu_class'      => 'clear',
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
						'depth'           => 1,
					) );
				}
			?>
			<div class="site-info">
				<a href="http://wordpress.org/" rel="generator"><?php printf( __( 'Proudly powered by %s', 'illustratr' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'illustratr' ), 'Illustratr', '<a href="http://wordpress.com/themes/illustratr/" rel="designer">WordPress.com</a>' ); ?>
			</div><!-- .site-info -->
		</div><!-- .footer-area -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>