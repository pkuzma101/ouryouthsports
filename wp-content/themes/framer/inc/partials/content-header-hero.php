<?php
/**
 * The template part for displaying a hero banner on the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package framer
 */

$framer_display_hero_banner = esc_attr(get_theme_mod( 'framer_hero_show', 'yes' ));
$framer_display_hero_button1 = esc_attr(get_theme_mod( 'framer_hero_button1_show', 'yes' ));
$framer_display_hero_button2 = esc_attr(get_theme_mod( 'framer_hero_button2_show', 'yes' ));

if ( $framer_display_hero_banner === 'yes' ) :
	?>
<?php if ( 'page' == get_option( 'show_on_front' )): ?>
	<h2><?php echo esc_html( get_theme_mod( 'framer_hero_title' ) ); ?></h2>

	<?php

	echo '<p class="herotext">' . wp_kses_post( get_theme_mod( 'framer_hero_text' ) ) . '</p>';

	echo '<div class="herobuttons">';

	if ( $framer_display_hero_button1 === 'yes' ) {
		echo '<a href="' . esc_url( get_theme_mod( 'framer_hero_button1_link', '#' ) ) . '" class="button seethrough large">' . esc_html( get_theme_mod( 'framer_hero_button1_text' ) ) . '</a>';
	}
	if ( $framer_display_hero_button2 === 'yes' ) {
		echo '<a href="' . esc_url( get_theme_mod( 'framer_hero_button2_link', '#' ) ) . '" class="button seethrough large">' . esc_html( get_theme_mod( 'framer_hero_button2_text' ) ) . '</a>';
	}
echo '</div>';
	?>

<?php endif; endif;