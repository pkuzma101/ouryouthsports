<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Illustratr-Child
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> style="margin-top:0px !important;">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<style>
  nav#navbar {
    height: 50px;
    width: 100%;
    background: #aeaeae;
  }
  div#head_img_box {
    height: 515px;
    width: 30%;
    margin: 0 auto;
    background-image: url("/ouryouthsports/wp-content/uploads/2017/05/OYS_Logo.png");
    background-size: 100% auto;
  }
</style>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
  <!--<nav id="navbar">
    <ul id="header_op_list">
      <? if (is_user_logged_in() == 0): ?>
        <li><a href="/ouryouthsports/wp-login.php">Login</a></li>
      <? else: ?>
      <li><a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
      <li><a href="my-leagues">My Leagues</a></li>
      <li><a href="add-league">New League</a></li>
      <? endif ?>
      <? if (get_current_user_id() == 1): ?>
      <li><a href="approve-league">Approve Leagues</a></li>
      <? endif ?>
    </ul>
  </nav>-->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/ouryouthsports/">Home</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <? if (is_user_logged_in() == 0): ?>
        <li class="active"><a href="/ouryouthsports/wp-login.php">Login/Sign Up <span class="sr-only">(current)</span></a></li>
        <? else: ?>
        <li class="active"><a href="<?php echo wp_logout_url(home_url()); ?>">Logout <span class="sr-only">(current)</span></a></li>
        <li><a href="my-leagues">My Leagues</a></li>
        <li><a href="add-league">New League</a></li>
        <? endif ?>
        <li><a href="find-event">Find Program</a></li>
      </ul>
      <? if (current_user_can('administrator')): ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="approve-league">Approve Programs</a></li>
        <li><a href="manage-sport">Manage Sports</a></li>
        <li><a href="manage-region">Manage Regions</a></li>
        <li><a href="manage-ads">Manage Ads</a></li>
      </ul>
      <? endif ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	<header id="masthead" class="site-header" role="banner">
		<?php
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) :
		?>
			<!-- <div class="site-image"> -->
				<!-- <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" class="header-image" /></a> -->
			<!-- </div>.site-image -->
		<?php endif; ?>

		<!-- <div class="site-branding"> -->
			<?php illustratr_the_site_logo(); ?>
			<!-- <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="/ouryouthsports/wp-content/uploads/2017/05/OYS_Logo.png"></a> -->
		<!-- </div>.site-branding -->

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h1 class="menu-toggle"><span class="genericon genericon-menu"><span class="screen-reader-text"><?php _e( 'Menu', 'illustratr' ); ?></span></span></h1>
				<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'illustratr' ); ?></a>

				<?php
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'container_class' => 'menu-wrapper',
						'menu_class'      => 'clear',
					) );
				?>
			</nav><!-- #site-navigation -->
		<?php endif; ?>
	</header><!-- #masthead -->
  <div id="head_img_box"></div>
	<div id="content" class="site-content">
