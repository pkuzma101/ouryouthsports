<?php
/**
 * The template part for displaying social links on the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package framer
 */

$framer_display_social_links = esc_attr(get_theme_mod( 'framer_header_social_show', 'yes' ));

if ( $framer_display_social_links === 'yes' ) :
	$framer_facebook_url = ( get_theme_mod( 'framer_header_social_facebook' ) );
	$framer_twitter_url = ( get_theme_mod( 'framer_header_social_twitter' ) );
	$framer_pinterest_url = ( get_theme_mod( 'framer_header_social_pinterest' ) );
	$framer_linkedin_url = ( get_theme_mod( 'framer_header_social_linkedin' ) );
	$framer_gplus_url = ( get_theme_mod( 'framer_header_social_gplus' ) );
	$framer_behance_url = ( get_theme_mod( 'framer_header_social_behance' ) );
	$framer_dribbble_url = ( get_theme_mod( 'framer_header_social_dribbble' ) );
	$framer_flickr_url = ( get_theme_mod( 'framer_header_social_flickr' ) );
	$framer_500px_url = ( get_theme_mod( 'framer_header_social_500px' ) );
	$framer_reddit_url = ( get_theme_mod( 'framer_header_social_reddit' ) );
	$framer_wordpress_url = ( get_theme_mod( 'framer_header_social_wordpress' ) );
	$framer_youtube_url = ( get_theme_mod( 'framer_header_social_youtube' ) );
	$framer_soundcloud_url = ( get_theme_mod( 'framer_header_social_soundcloud' ) );
	$framer_medium_url = ( get_theme_mod( 'framer_header_social_medium' ) );

	?>

		<ul class="socialmediamenu">
		<?php
		if ( !empty( $framer_facebook_url ) ) {
			echo '<li class="facebook"><a href="' . esc_url( $framer_facebook_url ) . '"><i class="fa fa-facebook"></i></a></li>';
		}
		if ( !empty( $framer_twitter_url ) ) {
			echo '<li class="twitter"><a href="' . esc_url( $framer_twitter_url ) . '"><i class="fa fa-twitter"></i></a></li>';
		}
		if ( !empty( $framer_pinterest_url ) ) {
			echo '<li class="pinterest"><a href="' . esc_url( $framer_pinterest_url ) . '"><i class="fa fa-pinterest"></i></a></li>';
		}
		if ( !empty( $framer_linkedin_url ) ) {
			echo '<li class="linkedin"><a href="' . esc_url( $framer_linkedin_url ) . '"><i class="fa fa-linkedin"></i></a></li>';
		}
		if ( !empty( $framer_gplus_url ) ) {
			echo '<li class="gplus"><a href="' . esc_url( $framer_gplus_url ) . '"><i class="fa fa-google-plus"></i></a></li>';
		}
		if ( !empty( $framer_behance_url ) ) {
			echo '<li class="behance"><a href="' . esc_url( $framer_behance_url ) . '"><i class="fa fa-behance"></i></a></li>';
		}
		if ( !empty( $framer_dribbble_url ) ) {
			echo '<li class="dribbble"><a href="' . esc_url( $framer_dribbble_url ) . '"><i class="fa fa-dribbble"></i></a></li>';
		}
		if ( !empty( $framer_flickr_url ) ) {
			echo '<li class="flickr"><a href="' . esc_url( $framer_flickr_url ) . '"><i class="fa fa-flickr"></i></a></li>';
		}
		if ( !empty( $framer_500px_url ) ) {
			echo '<li class="social500px"><a href="' . esc_url( $framer_500px_url ) . '"><i class="fa fa-500px"></i></a></li>';
		}
		if ( !empty( $framer_reddit_url ) ) {
			echo '<li class="reddit"><a href="' . esc_url( $framer_reddit_url ) . '"><i class="fa fa-reddit"></i></a></li>';
		}
		if ( !empty( $framer_wordpress_url ) ) {
			echo '<li class="wordpress"><a href="' . esc_url( $framer_wordpress_url ) . '"><i class="fa fa-wordpress"></i></a></li>';
		}
		if ( !empty( $framer_youtube_url ) ) {
			echo '<li class="youtube"><a href="' . esc_url( $framer_youtube_url ) . '"><i class="fa fa-youtube"></i></a></li>';
		}
		if ( !empty( $framer_soundcloud_url ) ) {
			echo '<li class="soundcloud"><a href="' . esc_url( $framer_soundcloud_url ) . '"><i class="fa fa-soundcloud"></i></a></li>';
		}
		if ( !empty( $framer_medium_url ) ) {
			echo '<li class="medium"><a href="' . esc_url( $framer_medium_url ) . '"><i class="fa fa-medium"></i></a></li>';
		}
		?>
	</ul>

<?php endif;