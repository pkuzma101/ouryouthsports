<?php
/**
 * framer functions and definitions
 *
 * @package framer
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
global $content_width;
	if ( !isset( $content_width ) ) {
	$content_width = 1060; /* pixels */
}

if ( !function_exists( 'framer_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function framer_setup()
	{

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on framer, use a find and replace
		 * to change 'framer' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'framer', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		
		/**
	Custom Logo
	 */
	add_theme_support( 'custom-logo', array(
	'height'      => 300,
	'width'       => 600,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		) );
		
		// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
	'primary' => esc_html__( 'Primary Menu', 'framer' ),
) );

		// enable featured images
		add_theme_support( 'post-thumbnails' );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'framer_custom_background_args', array(
			'default-color' => '#fff',
			'default-image' => '',
			'panel'         => 'framer_colors',
		) ) );

		add_image_size( 'framer-full', 1060, 700, true );
		add_image_size( 'framer-blog-thumb', 690, 542, true ); 
	}
endif;
add_action( 'after_setup_theme', 'framer_setup' );


// WooCommerce Support
add_action( 'after_setup_theme', 'framer_woocommerce_support' );
function framer_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}


// Style the Tag Cloud
function framer_tag_cloud_widget( $args )
{
	$args['largest'] = 12; //largest tag
	$args['smallest'] = 12; //smallest tag
	$args['unit'] = 'px'; //tag font unit
	$args['number'] = '8'; //number of tags
	return $args;
}

add_filter( 'widget_tag_cloud_args', 'framer_tag_cloud_widget' );


// add custom class to tags
function framer_add_class_the_tags( $html )
{
	$html = str_replace( '<a', '<a class="button seethrough small"', $html );

	return $html;
}

add_filter( 'the_tags', 'framer_add_class_the_tags' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function framer_widgets_init()
{

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'framer' ),
		'id'            => 'framer-footer',
		'before_widget' => '<div class="col-1-4"><div class="wrap-col"><div class="footerwidget">',
		'after_widget'  => '</div></div></div>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );

}

add_action( 'widgets_init', 'framer_widgets_init' );

// Load Roboto Font
function framer_fonts_url()
{
	$fonts_url = '';
	$font_families = array();

	// default fonts - Roboto and Arimo
	$roboto = _x( 'on', 'Montserrat font: on or off', 'framer' );
	$arimo = _x( 'on', 'Arimo font: on or off', 'framer' );
	$heading_font_family = esc_attr(get_theme_mod( 'framer_google_fonts_heading_font', null ));
	$body_font_family = esc_attr(get_theme_mod( 'framer_google_fonts_body_font', null ));

	if ( 'off' !== $roboto ) {
		$font_families[] = 'Montserrat, sans-serif;:400,700';
	}

	if ( 'off' !== $arimo ) {
		$font_families[] = 'Arimo:400,400italic,700,700italic';
	}

	if ( !empty( $heading_font_family ) && $heading_font_family !== 'none' ) {
		$heading_font = _x( 'on', $heading_font_family . ' font: on or off', 'framer' );
		if ( 'off' !== $heading_font ) {
			$font_families[] = $heading_font_family;
		}
	}

	if ( !empty( $body_font_family ) && $body_font_family !== 'none' && $body_font_family !== $heading_font_family ) {
		$body_font = _x( 'on', $body_font_family . ' font: on or off', 'framer' );
		if ( 'off' !== $body_font ) {
			$font_families[] = $body_font_family;
		}
	}


	// if both body and heading fonts are set in customizer,
	// don't include default Roboto and Arimo fonts
	if ( count( $font_families ) === 4 ) {
		array_slice( $font_families, 2 );
	}

	if ( !empty( $font_families ) ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles.
 */
function framer_scripts()
{
	wp_enqueue_style( 'framer-style', get_stylesheet_uri() );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/inc/css/font-awesome.min.css' );
	wp_enqueue_style( 'framer-fonts', framer_fonts_url(), array(), null );
	wp_enqueue_script( 'framer-footer-scripts', get_template_directory_uri() . '/inc/js/script.js', array( 'jquery' ), '20151107', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'framer_scripts' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis) and sets character length to 35
 */
/**
 * Filter the except length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function framer_custom_excerpt_length( $length )
{
	return 20;
}

add_filter( 'excerpt_length', 'framer_custom_excerpt_length', 999 );

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function framer_excerpt_more( $more )
{
	return '';
}

add_filter( 'excerpt_more', 'framer_excerpt_more' );

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/themesetup.php';
require get_template_directory() . '/inc/customizer.php';