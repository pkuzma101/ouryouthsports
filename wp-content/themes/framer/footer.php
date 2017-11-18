<div id="footer">
	<div class="wrapper">
		<div class="row">

			<?php dynamic_sidebar( 'framer-footer' ); ?>
		</div>
	</div>



<div id="bottom">
	<div class="wrapper">
		<p class="bottomtext">
			&copy; <?php bloginfo( 'name' ); ?> <?php echo date_i18n( __( 'Y', 'framer' ) ); ?> / <?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'framer' ), 'Framer', '<a href="http://themefurnace.com" rel="designer">ThemeFurnace</a>' ); ?>
		</p>
	</div>
	<!-- End wrapper -->
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>