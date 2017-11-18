<?php
/**
 * Template name: Homepage
 *
 * The main template file.
 *
 * @package framer
 */

get_header();

get_template_part( 'inc/partials/content', 'home-intro' );
get_template_part( 'inc/partials/content', 'home-static' );

get_footer();