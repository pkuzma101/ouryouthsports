===============
Framer
===============


Tags: grid-layout, right-sidebar,  one-column, two-columns, sticky-post,theme-options,custom-background, custom-menu, featured-images, threaded-comments, translation-ready, blog, portfolio
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Framer WordPress Theme, Copyright 2012 ThemeFurnace.com
Framer is distributed under the terms of the GNU GPL


Description
===============
Framer is a grid based portfolio theme - create a modern looking website for photography, graphic design, web design, logos or illustration and show off your work in style. framer is a light weight theme with no bloated features, control everything with the Customizer which lets you change the accent color, upload a hero header image and use the tools to create a color overlay or blur the image. Add your text and buttons and away you go!

Visit http://themefurnace.com to upgrade for $49 for 20 more themes, support and more theme features.

To Setup Homepage
===============

Create a new Page and choose the "homepage" template from the dropdown and publish it.
Set this as your homepage under "Settings" > "Reading"


Customizer
===============

Visit "Homepage Setup" to configure the homepage - Hero Banner, Social Icons and Latest Work Text.

Visit "Site Identity" to upload custom logo


License
===============
GNU General Public License v2 or later

Resources Used
===============

Font Awesome
License: SIL OFL 1.1
http://fortawesome.github.io/Font-Awesome/license/

Roboto Font
License: Apache License, version 2.0
http://www.apache.org/licenses/LICENSE-2.0.html

Arimo Font
License: Apache License, version 2.0
http://www.apache.org/licenses/LICENSE-2.0.html


Script.js / popular-recent.js / widget-author-box.js
GPL
http://www.gnu.org/licenses/gpl-3.0.en.html
ThemeFurnace.com




Header Photo
https://unsplash.com/photos/NsWcRlBT_74

Other Photos
https://unsplash.com/photos/9OKGEVJiTKk
https://unsplash.com/photos/KGcLJwIYiac
https://unsplash.com/photos/LUDk8uFb7gE
https://unsplash.com/photos/9SyOKYrq-rE


License: Creative Commons Zero
https://unsplash.com/license

All Other Images
ThemeFurnace.com
GNU General Public License v2 or later



Changelog
===============

1.0.0  	Initial Release

1.0.1   Added "Latest Work" Section to Customizer to change homepage text. Hover color fix for social icons.

1.0.2 	Fixes for WordPress.org Review

1.0.4 	Remove legacy code for footer logo and image.

1.0.6  	Default Accent Color Changed
	Final Fixes for Directory Listing:
	Woocommerce function prefix added "framer_woocommerce_support"
	Demo content on activation removed
	Removed framer_esc_html
	Changed framer_pagination to the_posts_pagination
	Removed the_archive_title
	Removed framer_the_posts_navigation and framer_the_post_navigation
	Removed framer Prefix from framer-font-awesome
	Removed extra function framer_sanitize_integer
	Added Frontpage Template
	Added un-minified Fontawesome
	Added instructions and license to readme.txt
	


Changelog
===============

1.0.0  	Initial Release

1.0.1   Added "Latest Work" Section to Customizer to change homepage text. Hover color fix for social icons.

1.0.2 	Fixes for WordPress.org Review

1.0.4 	Remove legacy code for footer logo and image.

1.0.6  	Default Accent Color Changed
	Final Fixes for Directory Listing:
	Woocommerce function prefix added "framer_woocommerce_support"
	Demo content on activation removed
	Removed framer_esc_html
	Changed framer_pagination to the_posts_pagination
	Removed the_archive_title
	Removed framer_the_posts_navigation and framer_the_post_navigation
	Removed framer Prefix from framer-font-awesome
	Removed extra function framer_sanitize_integer

1.0.7	Remove framer_pagination() function.
	front-page handling - hide the hero text, buttons and social media if the theme uses index.php and show if front page display set to static 		page -> homepage template
	wp_kses fixes

1.0.8	Remove Google Fonts

1.0.9	Removed old nanobar code from footer
	Removed framer_the_archive_description
	Changed <?php echo date( 'Y' ); ?> to <?php echo date_i18n( __( 'Y', 'framer' ) ); ?> in footer
	Moved $content_width above line 12 in functions	
	Moved register_nav_menus inside after_setup_theme
	Removed <meta name="format-detection" content="telephone=no"> from header
	header-archives.php now uses the_archive_title() and the_archive_description()
	Fixed esc_htmlesc_html_e on content-none.php
	Removed inline styling on search.php	
	Removed sticky-post theme tag
	Changed framer-frontpage-blog to framer-featured-image

1.0.10	Fixed more instances of esc_htmlesc_html_e
	Added post navigation to content-single.php

1.0.11 	Added global $content_width; to functions.php


1.0.12 	Changed Hero Banner Main Text to simple text field
	Changed default Hero Banner Main Text to a description of the field	
	Removed defaults for Social media icons in Hero Banner
	Changed Latest Work Test to simple text field
	Header-inside.php changed to <?php the_time( get_option( 'date_format' ) ); ?>
	Added Support for Custom Header Image

1.0.13 	Added to custom-header.php 'header-text' => false,
	Removed from custom-header.php `framer_admin_header_image callback
	Fixed Photo Credits	


1.0.15 	Fixed Theme URI
	Front Page Settings
	Added Custom Header to Header Templates

1.0.16	Escaped Height and Width of Custom Header Image

1.0.17	Updated Screenshot
	Tidied Style.css
	