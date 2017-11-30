<?php
/**
 * Illustratr functions and definitions
 *
 * @package Illustratr
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 840; /* pixels */
}

if ( ! function_exists( 'illustratr_content_width' ) ) :
/**
 * Adjust the content width for image post format and single portfolio.
 */


function illustratr_content_width() {
	global $content_width;

	if ( 'image' == get_post_format() || ( is_singular() && 'jetpack-portfolio' == get_post_type() ) ) {
		$content_width = 1100;
	}
}
endif;
add_action( 'template_redirect', 'illustratr_content_width' );

if ( ! function_exists( 'illustratr_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function illustratr_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Illustratr, use a find and replace
	 * to change 'illustratr' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'illustratr', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
  // add_theme_support( 'post-thumbnails' );
	/**
	 * Editor styles.
	 */
	add_editor_style( 'editor-style.css' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 400, 300, true );
	add_image_size( 'illustratr-featured-image', 1100, 500, true );
	add_image_size( 'illustratr-portfolio-featured-image', 800, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'illustratr' ),
		'social'  => __( 'Social Menu', 'illustratr' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'illustratr_custom_background_args', array(
		'default-color' => '24282d',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // illustratr_setup
add_action( 'after_setup_theme', 'illustratr_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function illustratr_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'illustratr' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Hidden footer area that is revealed by the + button at the bottom of each page', 'illustratr' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'illustratr_widgets_init' );

/**
 * Register Source Sans Pro font.
 *
 * @return string
 */
function illustratr_source_sans_pro_font_url() {
	$source_sans_pro_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Source Sans Pro, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Source Sans Pro font: on or off', 'illustratr' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Source Sans Pro character subset specific to your language, translate this to 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Source Sans Pro font: add new subset (vietnamese)', 'illustratr' );

		if ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		$query_args = array(
			'family' => urlencode( 'Source Sans Pro:400,700,900,400italic,700italic,900italic' ),
			'subset' => urlencode( $subsets ),
		);

		$source_sans_pro_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $source_sans_pro_font_url;
}

/**
 * Register PT Serif font.
 *
 * @return string
 */
function illustratr_pt_serif_font_url() {
	$pt_serif_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by PT Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'PT Serif font: on or off', 'illustratr' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional PT Serif character subset specific to your language, translate this to 'cyrillic'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'PT Serif font: add new subset (cyrillic)', 'illustratr' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic-ext,cyrillic';
		}

		$query_args = array(
			'family' => urlencode( 'PT Serif:400,700,400italic,700italic' ),
			'subset' => urlencode( $subsets ),
		);

		$pt_serif_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $pt_serif_font_url;
}

/**
 * Register Source Code Pro.
 *
 * @return string
 */
function illustratr_source_code_pro_font_url() {
	$source_code_pro_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Source Code Pro, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Source Code Pro font: on or off', 'illustratr' ) ) {

		$query_args = array(
			'family' => urlencode( 'Source Code Pro' ),
		);

		$source_code_pro_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $source_code_pro_font_url;
}

/**
 * Enqueue scripts and styles.
 */
function bootstrap() {
  wp_register_script( 'bootstrap-js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );

  wp_register_style( 'bootstrap-css', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), '3.3.7', 'all' );

  wp_enqueue_script( 'bootstrap-js' );

  wp_enqueue_style( 'bootstrap-css' );
}
add_action( 'wp_enqueue_scripts', 'bootstrap');

function illustratr_scripts() {
	wp_enqueue_style( 'illustratr-source-sans-pro', illustratr_source_sans_pro_font_url(), array(), null );

	wp_enqueue_style( 'illustratr-pt-serif', illustratr_pt_serif_font_url(), array(), null );

	wp_enqueue_style( 'illustratr-source-code-pro', illustratr_source_code_pro_font_url(), array(), null );

	if ( wp_style_is( 'genericons', 'registered' ) ) {
		wp_enqueue_style( 'genericons' );
	} else {
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );
	}

	wp_enqueue_style( 'illustratr-style', get_stylesheet_uri() );

	wp_enqueue_script( 'illustratr-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'illustratr-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		wp_enqueue_script( 'illustratr-sidebar', get_template_directory_uri() . '/js/sidebar.js', array( 'jquery', 'masonry' ), '20140325', true );
	}

	wp_enqueue_script( 'illustratr-script', get_template_directory_uri() . '/js/illustratr.js', array( 'jquery', 'underscore' ), '20140317', true );
}
add_action( 'wp_enqueue_scripts', 'illustratr_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @return void
 */
function illustratr_admin_fonts() {
	wp_enqueue_style( 'illustratr-source-sans-pro', illustratr_source_sans_pro_font_url(), array(), null );

	wp_enqueue_style( 'illustratr-pt-serif', illustratr_pt_serif_font_url(), array(), null );

	wp_enqueue_style( 'illustratr-source-code-pro', illustratr_source_code_pro_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'illustratr_admin_fonts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

add_action( 'wp_ajax_get_sports', 'get_sports' );
add_action( 'wp_ajax_nopriv_get_sports', 'get_sports' );
function get_sports() {
  global $wpdb;
  $sports = $wpdb->get_results("SELECT sport_name, sport_pic FROM sports WHERE sport_id BETWEEN 1 AND 6");
  ?>
  <div class="row" id="area_box">
    <? foreach($sports as $sport): ?>
    <div class="col-md-4">
      <a href="#" id="<?php echo $sport->sport_name; ?>" class="sport_link"><div class="thumbnail">
        <img src="<?php echo $sport->sport_pic; ?>" class="sport_pic">
        <div class="caption" style="font-size:1.9em; text-align:center; font-weight:bold;">
          <?php echo $sport->sport_name; ?>
        </div>
      </div></a>
    </div>
    <? endforeach ?>
  </div>
  <?php
  die();
}

add_action( 'wp_ajax_get_football_types', 'get_football_types' );
add_action( 'wp_ajax_nopriv_get_football_types', 'get_football_types' );
function get_football_types() {
  global $wpdb;
  $sports = $wpdb->get_results("SELECT sport_name, sport_pic FROM sports WHERE sport_name LIKE '%Football%'");
  ?>
  <div id="go_back_div"><a href="#" id="go_back_btn">Go Back</a></div>
  <div class="row" id="area_box">
    <? foreach($sports as $sport): ?>
    <div class="col-md-4">
      <a href="#" id="<?php echo $sport->sport_name; ?>" class="type_link"><div class="thumbnail">
        <img src="<?php echo $sport->sport_pic; ?>" class="sport_pic">
        <div class="caption" style="font-size:1.9em; text-align:center; font-weight:bold;">
          <?php echo $sport->sport_name; ?>
        </div>
      </div></a>
    </div>
    <? endforeach ?>
  </div>
  <?php
  die();
}

add_action( 'wp_ajax_get_events', 'get_events' );
add_action( 'wp_ajax_nopriv_get_events', 'get_events' );
function get_events() {
  ?>
  <div id="go_back_div"><a href="#" id="go_back_btn">Go Back</a></div>
  <div class="row">
    <div class="col-md-3">
      <a href="#" id="league" class="event_link"><div class="thumbnail">
        <div class="exp">Join a team and compete against other teams in this sport</div>
        <div class="caption">League</div>
      </div></a>
    </div>
    <div class="col-md-3">
      <a href="#" id="camp" class="event_link"><div class="thumbnail">
        <div class="exp">A camp where attendees learn specialized skills in a sport</div>
        <div class="caption">Camp</div>
      </div></a>
    </div>
    <div class="col-md-3">
      <a href="#" id="tournament" class="event_link"><div class="thumbnail">
        <div class="exp">Join a tournament and try to make it to the top</div>
        <div class="caption">Tournament</div>
      </div></a>
    </div>
    <div class="col-md-3">
      <a href="#" id="training" class="event_link"><div class="thumbnail">
        <div class="exp">Sign up to receive indivivualized instruction in a sport</div>
        <div class="caption">Training</div>
      </div></a>
    </div>
  </div>
  <?php

  die();
}

add_action( 'wp_ajax_get_regions', 'get_regions' );
add_action( 'wp_ajax_nopriv_get_regions', 'get_regions' );
function get_regions() {
  global $wpdb;

  $regions = $wpdb->get_results("SELECT CONCAT(' -- ', region_name) AS reg, area_name FROM regions");
  ?>
  <div id="go_back_div"><a href="#" id="go_back_btn">Go Back</a></div>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <a href="#" id="north" class="area_link"><div class="thumbnail">
        <div class="exp">
        <? foreach($regions as $region): ?>
        <?php if($region->area_name == 'north'): ?>
        <?php echo $region->reg; ?>
        <?php endif; ?>
        <? endforeach?>  
        </div>
        <div class="caption"><div>North</div></div>
      </div></a>
    </div>
    <div class="col-md-4"></div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <a href="#" id="west" class="area_link"><div class="thumbnail">
        <div class="exp">
        <? foreach($regions as $region): ?>
        <?php if($region->area_name == 'west'): ?>
        <?php echo $region->reg; ?>
        <?php endif; ?>
        <? endforeach?>
        </div>
        <div class="caption"><div>West</div></div>
      </div></a>
    </div>
    <div class="col-md-4">
      <a href="#" id="central" class="area_link"><div class="thumbnail">
        <div class="exp">
        <? foreach($regions as $region): ?>
        <?php if($region->area_name == 'central'): ?>
        <?php echo $region->reg; ?>
        <?php endif; ?>
        <? endforeach?>  
        </div>
        <div class="caption"><div>Central</div></div>
      </div></a>
    </div>
    <div class="col-md-4">
      <a href="#" id="east" class="area_link"><div class="thumbnail">
        <div class="exp">
        <? foreach($regions as $region): ?>
        <?php if($region->area_name == 'east'): ?>
        <?php echo $region->reg; ?>
        <?php endif; ?>
        <? endforeach?>
        </div>
        <div class="caption"><div>East</div></div>
      </div></a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <a href="#" id="south" class="area_link"><div class="thumbnail">
        <div class="exp">
        <? foreach($regions as $region): ?>
        <?php if($region->area_name == 'south'): ?>
        <?php echo $region->reg; ?>
        <?php endif; ?>
        <? endforeach?>  
        </div>
        <div class="caption"><div>South</div></div>
      </div></a>
    </div>
    <div class="col-md-4"></div>
  </div>
  <?php

  die();
}

add_action( 'wp_ajax_get_leagues', 'get_leagues' );
add_action( 'wp_ajax_nopriv_get_leagues', 'get_leagues' );
function get_leagues() {
  global $wpdb;
  $sel_sport = $_POST['selected_sport'];
  $sel_area = $_POST['selected_area'];

  $leagues = $wpdb->get_results("SELECT l.league_id, l.league_name, l.sport, l.league_region, l.link_to_site, 
                                        l.league_phone, l.league_fax, l.league_contact, l.league_email, l.league_logo,
                                        r.area_name, s.sport_pic  
                                 FROM leagues AS l 
                                 JOIN regions AS r ON l.league_region = r.region_name 
                                 JOIN sports AS s ON l.sport = s.sport_name 
                                 WHERE l.sport = '$sel_sport' AND r.area_name = '$sel_area' AND l.approved = 1 
                                 ORDER BY l.league_region");
  ?>
  <?php if (!empty($leagues)): ?>
  <div class="row" id="league_row">
    <?php foreach ($leagues as $league): ?>
    <div class="col-md-6">
      <div class="league_card" id="<?php echo $league->league_id; ?>">
        <div class="row">
          <div class="col-sm-6">
            <h4><?php echo $league->league_name; ?></h4>
            <h4><?php echo $league->sport; ?> - <?php echo $league->league_region; ?></h4>
          </div>
          <div class="col-sm-6">
            <?php if (!empty($league->league_logo)): ?>
            <div class="logo_div" style="background: url('<?php echo $league->league_logo; ?>');background-size:100% 100%;"></div>
            <? endif ?>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <span>Contact : <?php echo $league->league_contact; ?> - <a href="#"><?php echo $league->league_email; ?></a></span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 info_div"><span>Phone : <?php echo $league->league_phone; ?></span></div>
          <div class="col-sm-6 info_div"><span>Fax : <?php echo $league->league_fax; ?></span></div>
        </div>
        <div class="row">
          <div class="col-sm-12"><a href="<?php echo $league->link_to_site; ?>">Link to Website</a></div>
        </div>
      </div><!-- league_card -->
    </div><!-- col-md-6 -->
    <? endforeach ?>
  <?php else: ?>
  <h3 style="text-align:center;color:white;">Sorry, none found</h3>
  <?php endif; ?>
  </div>
  <?php

  die();
}

add_action( 'wp_ajax_get_all_leagues', 'get_all_leagues' );
add_action( 'wp_ajax_nopriv_get_all_leagues', 'get_all_leagues' );
function get_all_leagues() {
  global $wpdb;
  $selection = $_POST['selection'];

  // get either all leagues or just admin created ones depending on what was selected
  if ($selection == 'all') {
    $leagues = $wpdb->get_results(" SELECT league_id, league_name, sport, league_region, link_to_site, 
                                          league_phone, league_fax, league_contact, league_email, league_logo 
                                    FROM leagues WHERE approved = 1");
  ?>
  <div class="row">
    <? foreach($leagues as $league): ?>
    <div class="col-md-4">
      <div class="league_card" id="<?php echo $league->league_id; ?>">
        <?php if (!empty($league->league_logo)): ?>
        <img class="background_logo" src="<?php echo $league->league_logo; ?>">
        <? endif ?>
        <h4><?php echo $league->league_name; ?></h4>
        <h4><?php echo $league->sport; ?> - <?php echo $league->league_region; ?></h4>
        <div class="info_div">
          <span>Contact : <?php echo $league->league_contact; ?> - 
            <a href="#"><?php echo $league->league_email; ?></a>
          </span>
        </div>
        <div class="row">
          <div class="col-md-6 info_div"><span>Phone : <?php echo $league->league_phone; ?></span></div>
          <div class="col-md-6 info_div"><span>Fax : <?php echo $league->league_fax; ?></span></div>
        </div>
        <div class="info_div">
          <span><a href="<?php echo $league->link_to_site; ?>">Link to Website</a></span>
        </div>
        <div class="button_div row" style="text-align:center;">
          <div class="col-md-6">
            <a href="update-league?id=<?php echo $league->league_id; ?>" class="btn btn-info">Update</a>
          </div>
          <div class="col-md-6">
            <a href="#" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div><!-- league_card -->
    </div><!-- col-md-4 -->
    <? endforeach ?>
  </div>
  <?php 
  }
  else {
    $leagues = $wpdb->get_results(" SELECT league_id, league_name, sport, league_region, link_to_site, 
                                          league_phone, league_fax, league_contact, league_email, league_logo 
                                    FROM leagues WHERE user_id = 1 AND approved = 1");
  ?>
  <div class="row">
    <? foreach($leagues as $league): ?>
    <div class="col-md-4">
      <div class="league_card" id="<?php echo $league->league_id; ?>">
        <?php if (!empty($league->league_logo)): ?>
        <img class="background_logo" src="<?php echo $league->league_logo; ?>">
        <? endif ?>
        <h4><?php echo $league->league_name; ?></h4>
        <h4><?php echo $league->sport; ?> - <?php echo $league->league_region; ?></h4>
        <div class="info_div">
          <span>Contact : <?php echo $league->league_contact; ?> - 
            <a href="#"><?php echo $league->league_email; ?></a>
          </span>
        </div>
        <div class="row">
          <div class="col-md-6 info_div"><span>Phone : <?php echo $league->league_phone; ?></span></div>
          <div class="col-md-6 info_div"><span>Fax : <?php echo $league->league_fax; ?></span></div>
        </div>
        <div class="info_div">
          <span><a href="<?php echo $league->link_to_site; ?>">Link to Website</a></span>
        </div>
        <div class="button_div row" style="text-align:center;">
          <div class="col-md-6">
            <a href="update-league?id=<?php echo $league->league_id; ?>" class="btn btn-info">Update</a>
          </div>
          <div class="col-md-6">
            <a href="#" class="btn btn-danger">Delete</a>
          </div>
        </div>
      </div><!-- league_card -->
    </div><!-- col-md-4 -->
    <? endforeach ?>
  </div>
  <?php
  }
  die();
}

add_action( 'wp_ajax_update_sport', 'update_sport' );
add_action( 'wp_ajax_nopriv_update_sport', 'update_sport' );
function update_sport() {
  global $wpdb;
  $sport_id = $_POST['data'];
  $sports = $wpdb->get_results("SELECT sport_id, sport_name FROM sports WHERE sport_id = " . $sport_id);
  foreach($sports as $sport) {
    $sport_id = $sport->sport_id;
    $sport_name = $sport->sport_name;
  }
  ?>
  <form method="post" id="edit_league_form" enctype="multipart/form-data">
    <input type="hidden" id="sport_id" name="sport_id" value="<?php echo $sport_id; ?>" />
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" id="sport_name" name="sport_name" value="<?php echo $sport_name; ?>" required="true" />
    </div>
    <div class="form-group">
      <label>Background Photo</label>
      <input type="file" class="form-control" id="sport_pic" name="sport_pic" />
    </div>
    <div class="form-group">
      <button type="submit" id="edit_btn" name="edit_btn" class="btn btn-primary">Update Sport</button>
    </div>
  </form>
  <?php
  die();
}

add_action( 'wp_ajax_get_my_events', 'get_my_events' );
add_action( 'wp_ajax_nopriv_get_my_events', 'get_my_events' );
function get_my_events() {
  global $wpdb;
  $event_type = $_POST['event_type'];
  $user_id = $_POST['user_id'];

  switch($event_type) {
    case "leagues":
      $leagues = $wpdb->get_results("SELECT league_id, league_name, sport, league_region, 
                                      link_to_site, league_phone, league_fax, league_logo, 
                                      approved, league_contact, league_email
                                     FROM leagues WHERE user_id = " . $user_id);
      ?>
      <div class="row" id="league_row">
        <? foreach ($leagues as $league): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $league->league_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $league->league_name; ?></h4>
                <h4><?php echo $league->sport; ?> - <?php echo $league->league_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($league->league_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $league->league_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $league->league_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $league->league_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $league->league_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $league->league_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $league->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="update-league?id=<?php echo $league->league_id . '&event_type=leagues'; ?>" class="btn btn-info update_btn">Update</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- league_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    case "camps":
      $camps = $wpdb->get_results("SELECT camp_id, camp_name, sport, camp_region, 
                                          start_date, end_date, link_to_site, camp_phone, 
                                          camp_fax, camp_logo, approved, camp_contact, camp_email
                                    FROM camps WHERE user_id = " . $user_id);
      ?>
      <div class="row" id="camp_row">
        <? foreach ($camps as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->camp_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->camp_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->camp_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->camp_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->camp_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->camp_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->camp_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->camp_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->camp_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="update-league?id=<?php echo $camp->camp_id . '&event_type=camps'; ?>" class="btn btn-info update_btn">Update</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- camp_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    case "tournaments":
      $tournaments = $wpdb->get_results("SELECT t_id, t_name, sport, t_region, 
                                          start_date, end_date, link_to_site, t_phone, 
                                          t_fax, t_logo, approved, t_contact, t_email
                                         FROM tournaments WHERE user_id = " . $user_id);
      ?>
      <div class="row" id="t_row">
        <? foreach ($tournaments as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->t_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->t_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->t_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->t_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->t_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->t_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->t_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->t_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->t_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="update-league?id=<?php echo $camp->t_id . '&event_type=tournmanets'; ?>" class="btn btn-info update_btn">Update</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- t_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    default:
      $trainings = $wpdb->get_results("SELECT train_id, train_name, sport, train_region, 
                                              start_date, end_date, link_to_site, train_phone, 
                                              train_fax, train_logo, approved, train_contact, train_email
                                       FROM trainings WHERE user_id = " . $user_id);
      ?>
      <div class="row" id="train_row">
        <? foreach ($trainings as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->train_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->train_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->train_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->train_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->train_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->train_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->train_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->train_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->train_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="update-league?id=<?php echo $camp->train_id . '&event_type=trainings'; ?>" class="btn btn-info update_btn">Update</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- train_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
  }

  die();
}

add_action( 'wp_ajax_approve_league', 'approve_league' );
add_action( 'wp_ajax_nopriv_approve_league', 'approve_league' );
function approve_league() {
  global $wpdb;
  $league_id = $_POST['data'];
  $event_type = $_POST['event_type'];
  switch ($event_type) {
    case "camps":
      $wpdb->update('camps', array('approved' => 1),array('camp_id'=>$league_id));
      break;
    case "tournaments":
      $wpdb->update('tournaments', array('approved' => 1),array('t_id'=>$league_id));
      break;
    case "trainings":
      $wpdb->update('trainings', array('approved' => 1),array('train_id'=>$league_id));
      break;
    default:
      $wpdb->update('leagues', array('approved' => 1),array('league_id'=>$league_id));
  }
  die();
}

add_action( 'wp_ajax_get_unapproved_programs', 'get_unapproved_programs' );
add_action( 'wp_ajax_nopriv_get_unapproved_programs', 'get_unapproved_programs' );
function get_unapproved_programs() {
  global $wpdb;
  $event_type = $_POST['event_type'];
  switch ($event_type) {
    case "camps":
      $camps = $wpdb->get_results("SELECT c.camp_id, c.camp_name, c.sport, c.camp_region, 
                                          c.link_to_site, c.start_date, c.end_date, c.camp_phone, 
                                          c.camp_fax, c.camp_logo, c.camp_contact, c.camp_email,
                                          u.user_login, u.user_email, u.display_name
                                   FROM camps AS c
                                   INNER JOIN wp_users AS u ON u.ID = c.user_id 
                                   WHERE c.approved = 0");
      ?>
      <div class="row" id="camp_row">
        <? foreach ($camps as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->camp_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->camp_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->camp_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->camp_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->camp_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->camp_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->camp_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->camp_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->camp_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-success approve_btn">Approve</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- camp_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    case "tournaments":
      $tournaments = $wpdb->get_results("SELECT c.t_id, c.t_name, c.sport, c.t_region, 
                                          c.link_to_site, c.start_date, c.end_date, c.t_phone, 
                                          c.t_fax, c.t_logo, c.t_contact, c.t_email,
                                          u.user_login, u.user_email, u.display_name
                                   FROM camps AS c
                                   INNER JOIN wp_users AS u ON u.ID = c.user_id 
                                   WHERE c.approved = 0");
      ?>
      <div class="row" id="t_row">
        <? foreach ($tournaments as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->t_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->t_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->t_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->t_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->t_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->t_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->t_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->t_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->t_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-success approve_btn">Approve</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- t_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    case "trainings":
      $trainings = $wpdb->get_results("SELECT c.train_id, c.train_name, c.sport, c.train_region, 
                                              c.link_to_site, c.startrain_date, c.end_date, c.train_phone, 
                                              c.train_fax, c.train_logo, c.train_contact, c.train_email,
                                              u.user_login, u.user_email, u.display_name
                                       FROM trainings AS c
                                       INNER JOIN wp_users AS u ON u.ID = c.user_id 
                                       WHERE c.approved = 0");
      ?>
      <div class="row" id="train_row">
        <? foreach ($trainings as $camp): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $camp->train_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $camp->train_name; ?></h4>
                <h4><?php echo $camp->sport; ?> - <?php echo $camp->train_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($camp->train_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $camp->train_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $camp->train_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $camp->train_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $camp->train_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $camp->train_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $camp->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-success approve_btn">Approve</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- t_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
      <?php
      break;
    default:
      $leagues = $wpdb->get_results("SELECT l.league_id, l.league_name, l.sport, l.league_region, 
                                            l.link_to_site, l.league_phone, l.league_fax,  l.league_logo, 
                                            l.approved, l.league_contact, l.league_email,u.user_login, 
                                            u.user_email, u.display_name
                                     FROM leagues AS l
                                     INNER JOIN wp_users AS u ON u.ID = l.user_id
                                     WHERE l.approved = 0");
      ?>
      <div class="row" id="train_row">
        <? foreach ($leagues as $league): ?>
        <div class="col-md-6">
          <div class="league_card" id="<?php echo $league->league_id; ?>">
            <div class="row">
              <div class="col-sm-6">
                <h4><?php echo $league->league_name; ?></h4>
                <h4><?php echo $league->sport; ?> - <?php echo $league->league_region; ?></h4>
              </div>
              <div class="col-sm-6">
                <?php if (!empty($league->league_logo)): ?>
                <div class="logo_div" style="background: url('<?php echo $league->league_logo; ?>');background-size:100% 100%;"></div>
                <? endif ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <span>Contact : <?php echo $league->league_contact; ?> - <a href="#" data-toggle="modal" data-target="#email_modal"><?php echo $league->league_email; ?></a></span>
              </div>
              <div class="col-sm-6 info_div"><span>Phone : <?php echo $league->league_phone; ?></span></div>
              <div class="col-sm-6 info_div"><span>Fax : <?php echo $league->league_fax; ?></span></div>
              <div class="col-sm-12"><a href="<?php echo $league->link_to_site; ?>">Link to Website</a></div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-success approve_btn">Approve</a>
              </div>
              <div class="col-md-6" style="text-align:center;">
                <a href="#" class="btn btn-danger delete_btn">Delete</a>
              </div>
            </div>
          </div><!-- t_card -->
        </div><!-- col-md-6 -->
        <? endforeach ?>
      </div>
    <?php
  }
  die();
}

add_action( 'wp_ajax_delete_league', 'delete_league' );
add_action( 'wp_ajax_nopriv_delete_league', 'delete_league' );
function delete_league() {
  global $wpdb;
  $league_id = $_POST['data'];
  $event_type = $_POST['event_type'];
  switch ($event_type) {
    case "camps":
      $wpdb->query("DELETE FROM camps WHERE camp_id = " . $league_id);
      break;
    case "tournaments":
      $wpdb->query("DELETE FROM tournaments WHERE t_id = " . $league_id);
      break;
    case "trainings":
      $wpdb->query("DELETE FROM trainings WHERE train_id = " . $league_id);
      break;
    default:
      $wpdb->query("DELETE FROM leagues WHERE league_id = " . $league_id);
  }
  die();
}


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/**
 * Load plugin enhancement file to display admin notices.
 */
require get_template_directory() . '/inc/plugin-enhancements.php';