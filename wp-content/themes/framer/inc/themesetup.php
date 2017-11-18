<?php

// load colors
function framer_load_theme_colors()
{

	$bodyBackgroundColor = esc_attr( get_theme_mod( 'framer_body_background_color', '#fff;' ) );
	$accentColor = esc_attr( get_theme_mod( 'framer_accent_color', '#57c66d' ) );
	$heroImageOverlayColor = esc_attr( get_theme_mod( 'framer_hero_overlay_color', '#000' ) );
	$heroImageOverlayOpacity = esc_attr( get_theme_mod( 'framer_hero_overlay_opacity', '80' ) );
	$heroImageBlur = esc_attr( get_theme_mod( 'framer_hero_blur_enabled', '0' ) );
	$hero_image_bg = esc_url( get_theme_mod( 'framer_hero_bg_image' ) );
	echo '<style type="text/css">';

	if ( !empty( $bodyBackgroundColor ) ) {
		$hash = '';
		if ( strpos( $bodyBackgroundColor, '#' ) === false ) {
			$hash = '#';
		}
		echo 'body { background-color: ' . $hash . $bodyBackgroundColor . '}';
	}


	if ( !empty( $accentColor ) ) {
		$hash = '';
		if ( strpos( $accentColor, '#' ) === false ) {
			$hash = '#';
		}
		echo '#hero .fa:hover, .postcontentmeta a:hover, #bottom a:hover, .herotext a:hover, #hero .button, a,  .featurewidgeticon .fa,  .profile_cont .fa, .postcontentmeta .fa, .authormeta a, .post-edit-link, #content a, #comments a, #respond a { color: ' . $hash . $accentColor . '} ';
		echo '.postmeta li a:hover, .sidebarwidget .fa:hover, .sidebarwidget li a:hover, #cssmenu > ul > li:hover > a {color: ' . $hash . $accentColor . '} ';
		echo '#introarea, .page-links a, .tab_head li:hover, .blogimage .fa, .search input.submit, .tab_head li:hover, .tab_head li.active, #hero .green, .pagination a, .pagination span{background-color: ' . $hash . $accentColor . '} ';
		echo '::selection {background-color: ' .$hash.$accentColor. '}';
		echo '*::-moz-selection, .blogimageoverlay {background-color: ' .$hash.$accentColor. '}';
		echo '.main-navigation ul ul a:hover {background-color: ' .$hash.$accentColor. '}';
		//echo '.menu-item.has-sub a::before, .menu-item.has-sub a::after {background: ' .$hash . $accentColor. ' !important;}';
		echo 'h1.site-title a, .herotext a, .footerwidget a, h1.site-title, .postcontent a:hover, .authormeta a:hover, .post-edit-link:hover .sidebarwidget a:hover, .tab_cont .clear, .logged-in-as a:hover {border-color: ' . $hash . $accentColor . ' } ';
		echo '.tab_head li:hover a {color: #fff !important;}';
	}
	
	

	echo '#hero .hero-overlay {background-color: '.$heroImageOverlayColor.'; opacity: '.round($heroImageOverlayOpacity / 100, 2).'}';
	echo '#hero .hero-bg {filter: blur('.$heroImageBlur.'px); -webkit-filter: blur('.$heroImageBlur.'px);}';
	echo '#hero .hero-bg { background-image: ' . ( $hero_image_bg ? 'url(' . $hero_image_bg . ')' : 'none' ) . '}';
	echo '</style>';
}

add_action( 'wp_head', 'framer_load_theme_colors' );