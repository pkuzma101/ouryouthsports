<?php
/**
 * @package framer
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses framer_header_style()
 * @uses framer_admin_header_style()
 * @uses framer_admin_header_image()
 */
function framer_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'framer_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '495762',
		'width'                  => 1600,
		'height'                 => 630,
		'default-image' => get_template_directory_uri() . '/images/header.jpg',
		'flex-height'            => true,
		'header-text' 			=> false,
	) ) );
}
add_action( 'after_setup_theme', 'framer_custom_header_setup' );