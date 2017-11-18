<?php
/*
Plugin Name: Event CLNDR
Description: An uncomplicated event manager with a highly customisable (developer-friendly) front-end calendar utilising CLNDR.js.
Author: Ian Dickety
Author URI: http://www.iandickety.co.uk
Text Domain: clndr
Version: 1.03
License: GNU General Public License v3.0
***Domain Path: /languages

Date: Ocotber 2016
Credits:
	"Calendar" Wordpress plugin by Kieran O'Shea (https://wordpress.org/plugins/calendar/)
		Loose base for back end source code
	"CLNDR.js" jQuery plugin by Kyle Stetz (http://kylestetz.github.io/CLNDR/)
		Entire front end implementation
	"Codemirror" - Editable syntax highlihgter (https://codemirror.net/)
		Used for code editors on Instances and Styles admin pages
	"Pikaday" - Lightweight date picker library (http://dbushell.github.io/Pikaday/)
		Used for date pickers on Events admin page
*/

// Enable internationalisation
/*
***** Currently no translations to incorporate  *****
*$plugin_dir = plugin_basename(dirname(__FILE__));
*load_plugin_textdomain( 'clndr',false, $plugin_dir.'/languages');
*/

// Define the tables used in CLNDR
global $wpdb;
define('CLNDR_EVENTS_TABLE', $wpdb->prefix . 'clndr_events');
define('CLNDR_INSTANCES_TABLE', $wpdb->prefix . 'clndr_instances');
define('CLNDR_VERSION', "1.03");
define('CLNDR_DIR_URL', plugin_dir_url(__FILE__));

//check to see if the db is installed or needs updating
add_action( 'plugins_loaded', 'CLDNR_init' );

//create a master category for CLNDR and its sub-pages
add_action('admin_menu', 'admin_CLNDR_menu');

//tie our scripts to wp
add_action('wp_enqueue_scripts', 'CLNDR_stylesheets');

//tie our scripts to admin
add_action('admin_enqueue_scripts', 'admin_CLNDR_stylesheets');

//register the widget and shortcode
add_shortcode( 'clndr', 'CLNDR_shortcode' );
add_filter('widget_text', 'do_shortcode');

//if the user uninstalls, cleanup
register_uninstall_hook(__FILE__, 'CLNDR_uninstall');

// Function to initialize clndr
function CLDNR_init() {
	global $wpdb;

  $installed_CLNDR_VERSION = get_option( "clndr_version" , null);

	//install/upgrade if necessary
  if ( $installed_CLNDR_VERSION != CLNDR_VERSION ) {

  	$charset_collate = $wpdb->get_charset_collate();

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "CREATE TABLE " . CLNDR_EVENTS_TABLE . " (
      event_id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
      event_begin DATE NOT NULL ,
      event_end DATE NOT NULL ,
      event_title VARCHAR(50) NOT NULL ,
      event_desc TEXT NOT NULL ,
      event_time VARCHAR(100) ,
      event_link VARCHAR(2048)  ,
      UNIQUE KEY event_id (event_id)
    ) $charset_collate;";

  	dbDelta( $sql );

    $sql = "CREATE TABLE " . CLNDR_INSTANCES_TABLE . " (
      instance_id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
      instance_name VARCHAR(60) NOT NULL ,
      instance_slug VARCHAR(60) NOT NULL ,
      instance_template TEXT NOT NULL ,
      instance_options TEXT NOT NULL ,
      instance_locale VARCHAR(40) DEFAULT 'en' NOT NULL ,
      instance_periods VARCHAR(40) DEFAULT '1,2,3' NOT NULL ,
      instance_limit INT(11) NOT NULL ,
      UNIQUE KEY instance_id (instance_id)
    ) $charset_collate;";

  	dbDelta( $sql );

		//if this is a new install let's be nice and prepoplate a couple of instances
		if ($installed_CLNDR_VERSION === false || is_null($installed_CLNDR_VERSION)) {

			$template = '
<div class="clndr-controls">
	<div class="current-month"><%= month %> <%= year %></div>
	<div class="clndr-nav clndr-clearfix">
		<div class="clndr-previous-button">‹</div>
		<div class="clndr-next-button">›</div>
	</div>
</div>
<div class="clndr-grid">
	<div class="days-of-the-week clndr-clearfix">
		<% _.each(daysOfTheWeek, function(day) { %>
		<div class="header-day"><%= day %></div>
		<% }); %>
	</div>
	<div class="days clndr-clearfix">
		<% _.each(days, function(day) { %>
		<div class="<%= day.classes %>" id="<%= day.id %>"><span class="day-number"><%= day.day %></span></div>
		<% }); %>
	</div>
</div>
<div class="event-listing">
	<div class="event-listing-title">Events</div>
	<% _.each(eventsThisMonth, function(event) { %>
		<% if (event.url) {  %><a target="_blank" href="<%= event.url %>" <% } else {  %><div <% }  %> class="event-item clndr-clearfix">
			<span class="event-item-date">
				<% if (event.end != event.start) {
					startMY = moment(event.start).format("MM YY");
					endMY = moment(event.end).format("MM YY");
					if (startMY === endMY) { %>
						<%= moment(event.start).format("D") %>–<%= moment(event.end).format("D MMMM") %>
					<% } else { %>
						<%= moment(event.start).format("D MMMM") %> – <%= moment(event.end).format("D MMMM") %>
					<% }
				} else {  %>
					<%= moment(event.start).format("D MMMM") %>
				<% } %>
			</span>
			<span class="event-item-name"><%= event.title %></span>
			<% if (event.time) {  %>
				<span class="event-item-time"><%= event.time %></span>
			<% } %>
			<% if (event.desc) {  %>
				<span class="event-item-desc"><%= event.desc %></span>
			<% } %>
		<% if (event.url) {  %></a><% } else {  %></div><% }  %>
	<% }); %>
</div>';

			$options = '
doneRendering: function() {
	var day=1, week=1, thisCLNDR = $(this)[0]["element"];
	thisCLNDR.find(".day").each(function() {
		if (day == 8) { day = 1; week++; }
		if (week % 2 === 0) { $(this).addClass("alternate-bg"); }
		day++;
	});

	var thisMonthEvents = thisCLNDR.find(".event-item").length;
	if (thisMonthEvents == 0) {
		thisCLNDR.find(".event-listing").append(
			"<div style=\'text-align:center;\' class=\'event-item\'>No events found</div>"
		);
	}
},
weekOffset: 1';


			$sql = $wpdb->prepare(
				"INSERT INTO " . CLNDR_INSTANCES_TABLE . " SET instance_name='%s', instance_slug='%s', instance_template='%s', instance_options='%s'", "Full size calendar", "full-size-calendar", $template, $options);
			$wpdb->query($sql);

			$sql = $wpdb->prepare(
				"INSERT INTO " . CLNDR_INSTANCES_TABLE .
				" SET instance_name='%s', instance_slug='%s', instance_template='%s', instance_options='%s'", "Mini calendar", "mini-calendar", $template, $options);
			$wpdb->query($sql);

		}

  	update_option( 'clndr_version', CLNDR_VERSION );
  }

}

function CLNDR_uninstall() {
	delete_option( 'clndr_version' );
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS " . CLNDR_EVENTS_TABLE);
	$wpdb->query("DROP TABLE IF EXISTS " . CLNDR_INSTANCES_TABLE);
}

/*
 * START OF FUNCTIONS FOR FRONT END CALENDAR
*/

// Function to enqueue scripts for our front end clndr
function CLNDR_stylesheets() {
	//our JS is enqueued when a shortcode is detected and placed in the footer
	//therefore is only loaded when needed. The CSS has go in the head and it's
	//inexpensive, so we'll load it on every page as it's the easiest option
	wp_register_style( 'clndr-styles.css', CLNDR_DIR_URL . 'css/clndr-styles.css');
	wp_enqueue_style( 'clndr-styles.css' );
}

/*
 * Function to deal with the shortcodes
 *
 * @param		array		$atts		the shortcode attributes
*/
function CLNDR_shortcode($atts) {
  $a = shortcode_atts( array(
      'id' => ''
  ), $atts );

  $instance = CLNDR_get($a["id"]);

  if ($instance) {
    return $instance;
  } else {
    return __("[ CLNDR instance not found ]");
  }

}

/*
 * Function to set up the actual clndr instances
 *
 * @param		string	$instance_id	the clndr instance to show
*/
function CLNDR_get($instance_slug) {

  global $wpdb;
  $instance = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . CLNDR_INSTANCES_TABLE . " WHERE instance_slug='%s'",$instance_slug),"ARRAY_A");

  if (!empty($instance)) {

		do_action("clndr_init", $instance["instance_slug"]);

    if ( ! wp_script_is( 'clndr', 'enqueued' )) {
      if ( ! wp_script_is( 'jquery', 'enqueued' )) {
        wp_enqueue_script( 'jquery' );
      }

      wp_register_script( 'underscore', CLNDR_DIR_URL . 'libs/clndr/underscore.min.js');
      wp_register_script( 'moment', CLNDR_DIR_URL . 'libs/clndr/moment-with-locales.min.js');
      wp_register_script( 'clndr', CLNDR_DIR_URL . 'libs/clndr/clndr.min.js');
      wp_enqueue_script( 'underscore' );
      wp_enqueue_script( 'moment' );
      wp_enqueue_script( 'clndr', false, array("jquery","underscore","moment"));
		}

		$template = $instance["instance_template"];
		$time_periods = explode(",",$instance["instance_periods"]);
		$limit = $instance["instance_limit"];

		$foot_func["locale"] = $instance["instance_locale"];
		$foot_func["element_ID"] = "clndr_".$instance["instance_slug"]."_".uniqid();
		$foot_func["options"] = $instance["instance_options"];

		$events = $wpdb->get_results("SELECT * FROM " . CLNDR_EVENTS_TABLE . " ORDER BY event_begin ASC", "ARRAY_A");

		$foot_func["events"] = "";

		if (!empty($events)) {
			$showCounter = 0;
			foreach ($events as $event) {

				$showThisEvent = false;
				$today = date("Y-m-d");

				if ($event["event_end"] < $today && in_array(1, $time_periods)) {
					$showThisEvent = true;
				} else if ($event["event_begin"] <= $today && $event["event_end"] >= $today && in_array(2, $time_periods)) {
					$showThisEvent = true;
				} else if ($event["event_begin"] > $today && in_array(3, $time_periods)) {
					$showThisEvent = true;
				}

				if ($showThisEvent) {
					if ($limit == 0 || $limit > $showCounter) {
						$foot_func["events"] .="{
							start: '".$event["event_begin"]."',
							end: '".$event["event_end"]."',
							title: '".$event["event_title"]."',
							desc: '".$event["event_desc"]."',
							url: '".$event["event_link"]."',
							time: '".$event["event_time"]."'
						},";
						$showCounter++;
					}
				}
			}
		}

		$foot_func["events"] = "[ " . rtrim($foot_func["events"], ",") . " ]";

		add_action("wp_print_footer_scripts", function() use ( $foot_func ) {
			?>
			<!-- CLNDR start -->

			<script>
				jQuery( function($) {
					
					moment.locale('<?php echo $foot_func["locale"]; ?>');

					var options = {
						template: $("#<?php echo $foot_func["element_ID"]; ?> .template-holder").html(),
						multiDayEvents: {
				      endDate: "end",
				      startDate: "start"
				    },
						events: <?php echo $foot_func["events"]; ?>
					}

					var userOptions = { <?php echo $foot_func["options"]; ?> };

					$.extend(true, options, userOptions );

					$("#<?php echo $foot_func["element_ID"]; ?>").clndr(options)
				});
			</script>
			<!-- CLNDR end -->
			<?php
		},100 );

		$html = '<div class="clndr-holder clndr_'.$instance["instance_slug"].'" id="'.$foot_func["element_ID"].'"><script class="template-holder" type="text/template">	'.$template.'</script></div>';
    return $html;

		do_action("clndr_loaded", $instance["instance_slug"]);

  } else {
    return false;
  }
}

/*
 * END OF FUNCTIONS FOR FRONT END CALENDAR
*/

// Function to add clndr to the admin menu
function admin_CLNDR_menu() {
  add_menu_page(__('Event CLNDR','clndr'), __('Event CLNDR','clndr'), 'edit_pages', 'clndr', 'admin_CLNDR_EVENTS','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSI1NnB4IiBoZWlnaHQ9IjU2cHgiIHZpZXdCb3g9IjAgMCA1NiA1NiIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNTYgNTYiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxnIGlkPSJzdmdfMSI+PGcgaWQ9InN2Z18yIj48cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNTIuOTg3LDU1LjQyOWgtNTBjLTEuMzgxLDAtMi41LTEuMTE5LTIuNS0yLjVWNi42NjdjMC0xLjM4MSwxLjExOS0yLjUsMi41LTIuNWg1MGMxLjM4MSwwLDIuNSwxLjExOSwyLjUsMi41djQ2LjI2MkM1NS40ODcsNTQuMzEsNTQuMzY4LDU1LjQyOSw1Mi45ODcsNTUuNDI5eiBNNS40ODcsNTAuNDI5aDQ1VjkuMTY3aC00NVY1MC40Mjl6Ii8+PC9nPjxnIGlkPSJzdmdfMyI+PHJlY3QgeD0iMi45ODciIHk9IjE3LjY5NSIgZmlsbD0iI0ZGRkZGRiIgd2lkdGg9IjUwIiBoZWlnaHQ9IjIiLz48L2c+PGcgaWQ9InN2Z180Ij48ZyBpZD0ic3ZnXzUiPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0zMS4xOTgsMzEuMjRjMC0wLjE4LTAuMDIxLTAuNDA1LTAuMDYtMC42NzRjLTAuMDM5LTAuMjctMC4xNDUtMC41MzUtMC4zMTktMC43OTVjLTAuMTc1LTAuMjYyLTAuNDM1LTAuNDg2LTAuNzcyLTAuNjc1Yy0wLjM0LTAuMTg4LTAuODItMC4yODMtMS40NDMtMC4yODNjLTAuODM2LDAtMS40ODIsMC4yMi0xLjkzOCwwLjY2MWMtMC40NTcsMC40NDEtMC42ODYsMS4xMTktMC42ODYsMi4wMzd2Ny4xNDhjMCwwLjkxOCwwLjIyOSwxLjU5OCwwLjY4NiwyLjAzOGMwLjQ1NSwwLjQ0LDEuMTExLDAuNjYxLDEuOTY4LDAuNjYxYzAuNTg0LDAsMS4wNDEtMC4wOSwxLjM3LTAuMjdjMC4zMy0wLjE4LDAuNTg1LTAuNDA2LDAuNzU5LTAuNjc1YzAuMTc2LTAuMjcxLDAuMjg3LTAuNTYyLDAuMzM2LTAuODc4YzAuMDQ5LTAuMzE0LDAuMDgyLTAuNjA2LDAuMTAyLTAuODc3YzAuMDM5LTAuNTAyLDAuMjc3LTAuODQsMC43MTUtMS4wMTFzMC45NDctMC4yNTcsMS41MzEtMC4yNTdjMC43OTcsMCwxLjM3OSwwLjEzMiwxLjc1LDAuMzkzYzAuMzY5LDAuMjYsMC41NTMsMC43OTUsMC41NTMsMS42MDRjMCwwLjg5OS0wLjE4NCwxLjctMC41NTMsMi40MDFjLTAuMzcxLDAuNzAyLTAuODg1LDEuMjkxLTEuNTQ3LDEuNzY4Yy0wLjY2LDAuNDc3LTEuNDM0LDAuODQtMi4zMTgsMS4wOTNjLTAuODg1LDAuMjUxLTEuODUyLDAuMzc4LTIuOSwwLjM3OGMtMC45NTMsMC0xLjg1Mi0wLjExMy0yLjY5Ny0wLjMzOGMtMC44NDYtMC4yMjUtMS41OS0wLjU5NC0yLjIzLTEuMTA1Yy0wLjY0My0wLjUxMy0xLjE0OC0xLjE3LTEuNTE4LTEuOTcxYy0wLjM3LTAuOC0wLjU1My0xLjc4NC0wLjU1My0yLjk1M3YtNy4xNWMwLTEuMTUsMC4xODQtMi4xMzIsMC41NTMtMi45NDFjMC4zNjktMC44MDksMC44NzUtMS40NywxLjUxOC0xLjk4M2MwLjY0MS0wLjUxNCwxLjM4NS0wLjg4MiwyLjIzLTEuMTA1YzAuODQ2LTAuMjI1LDEuNzQ0LTAuMzM4LDIuNjk3LTAuMzM4YzEuMDQ4LDAsMi4wMTYsMC4xMTcsMi45LDAuMzUxYzAuODg1LDAuMjM1LDEuNjU4LDAuNTgsMi4zMTgsMS4wMzljMC42NjIsMC40NTksMS4xNzYsMS4wMjEsMS41NDcsMS42ODZjMC4zNjksMC42NjcsMC41NTMsMS40MzEsMC41NTMsMi4yOTRjMCwwLjgwOS0wLjE4NCwxLjM0NS0wLjU1MywxLjYwNWMtMC4zNzEsMC4yNi0wLjk0MywwLjM5MS0xLjcyMSwwLjM5MWMtMC42MjMsMC0xLjE0OC0wLjA5LTEuNTc0LTAuMjdDMzEuNDY5LDMyLjA1OSwzMS4yMzcsMzEuNzI1LDMxLjE5OCwzMS4yNEwzMS4xOTgsMzEuMjR6Ii8+PC9nPjwvZz48ZyBpZD0ic3ZnXzgiPjxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xNi4zMjEsMTMuMTc2Yy0xLjM4MSwwLTIuNS0xLjExOS0yLjUtMi41VjIuNjU3YzAtMS4zODEsMS4xMTktMi41LDIuNS0yLjVzMi41LDEuMTE5LDIuNSwyLjV2OC4wMTlDMTguODIxLDEyLjA1NywxNy43MDIsMTMuMTc2LDE2LjMyMSwxMy4xNzZ6Ii8+PC9nPjxnIGlkPSJzdmdfOSI+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTM5LjY1NSwxMy4xNzZjLTEuMzgxLDAtMi41LTEuMTE5LTIuNS0yLjVWMi42NTdjMC0xLjM4MSwxLjExOS0yLjUsMi41LTIuNXMyLjUsMS4xMTksMi41LDIuNXY4LjAxOUM0Mi4xNTUsMTIuMDU3LDQxLjAzNiwxMy4xNzYsMzkuNjU1LDEzLjE3NnoiLz48L2c+PC9nPjwvZz48L3N2Zz4=');
	add_submenu_page('clndr', __('Events','clndr'), __('Events','clndr'), 'edit_pages', 'clndr', 'admin_CLNDR_EVENTS');
  add_submenu_page('clndr', __('Instances','clndr'), __('Instances','clndr'), 'manage_options', 'clndr-instances', 'admin_CLNDR_INSTANCES');
  add_submenu_page('clndr', __('Master Styles','clndr'), __('Master Styles','clndr'), 'manage_options', 'clndr-styles', 'admin_CLNDR_STYLES');
}

/*
 * START OF FUNCTIONS FOR ADMIN INSTANCES PAGE
*/

// Function to render the instances page and to deal with user input
function admin_CLNDR_INSTANCES() {

	admin_CLNDR_load_codemirror();

	global $wpdb, $users_entries;

	$CLNDR_input_errors = false;

  // Make sure we are collecting the variables we need to select years and months
  $action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
  $instance_id = !empty($_REQUEST['instance_id']) ? $_REQUEST['instance_id'] : '';
	$instance_id = admin_CLNDR_INSTANCES_verify_instance_id($instance_id);

	$name = !empty($_REQUEST['instance_name']) ? admin_CLNDR_sanitize_input_field($_REQUEST['instance_name']) : '';
	$template = !empty($_REQUEST['instance_template']) ? stripslashes($_REQUEST['instance_template']) : '';
	$options = !empty($_REQUEST['instance_options']) ? stripslashes($_REQUEST['instance_options']) : '';
	$locale = !empty($_REQUEST['instance_locale']) ? $_REQUEST['instance_locale'] : '';
	$periods = !empty($_REQUEST['instance_periods']) ? (array)$_REQUEST['instance_periods'] : array();
	$limit = !empty($_REQUEST['instance_limit']) ? $_REQUEST['instance_limit'] : '';

	if (!empty($action)) {

		//if the user is tring to do something
		if ($action == "add" || $action == "edit_save" || $action == "delete") {

			$nonce_end = ($action == "add" ? "" : "_".$instance_id);

			if (($action == "edit_save" || $action == "delete") && !$instance_id) { ?>
				<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Invalid instance ID",'clndr'); ?></p></div>
				<?php
			}
	  	elseif (wp_verify_nonce($_REQUEST['_wpnonce'],'clndr-instances-'.$action.$nonce_end) == false) {	?>
 		  	<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Security check failure, please try again",'clndr'); ?></p></div>
 				<?php
 			} else {
				if ($action == "add" || $action == "edit_save") {

					//ensures the instance name isn't already taken
					$slug = admin_CLNDR_INSTANCES_verify_instance_name($name,$instance_id);

					$validLocale = (in_array($locale,admin_CLNDR_INSTANCES_get_locales()) ? true : __("The locale is invalid"));
					$allPeriodsValid = true;
					$validLimit = ($limit == '' || ctype_digit($limit)) ? true : __("Max events has to be empty or numeric");

					if (is_array($periods)) {
						foreach ($periods as $tp) {
							if (!in_array($tp,array(1,2,3))) {
								$allPeriodsValid = __("One or more time periods are specified incorrectly");
							}
						}
						$periods = implode(",", $periods);
					}

			  	if ($slug["status"] == "success" && $validLocale === true && $allPeriodsValid === true && $validLimit === true) {

						$slug = $slug["slug"];

						$sql_main = CLNDR_INSTANCES_TABLE . " SET instance_name='%s', instance_slug='%s', instance_template='%s', instance_options='%s', instance_locale='%s', instance_periods='%s', instance_limit='%s'";

						if ($action == "add") {

				    	$sql = $wpdb->prepare("INSERT INTO " . $sql_main, $name, $slug, $template, $options, $locale, $periods, $limit);
							$wpdb->get_results($sql);
							?>

							<div class="updated"><p><?php _e('Instance succesfully added','clndr'); ?></p></div>

							<?php
						} else {

							$sql = $wpdb->prepare("UPDATE " . $sql_main . "  WHERE instance_id='%d'",	$name, $slug, $template, $options, $locale, $periods, $limit, $instance_id);
							$wpdb->get_results($sql);
							?>

							<div class="updated"><p><?php _e('Instance succesfully edited','clndr'); ?></p></div>

							<?php
						}

				    do_action('add_instance', $action);

				  }	else {
				    // The form is going to be rejected due to field validation issues, so we preserve the users entries here
						$users_entries = new stdClass();
				    $users_entries->instance_name = $name;
				    $users_entries->instance_template = $template;
				    $users_entries->instance_options = $options;
				    $users_entries->instance_locale = $locale;
				    $users_entries->instance_periods = $periods;
				    $users_entries->instance_limit = $limit;
						$CLNDR_input_errors = true;
						?>

						<div class="error"><ul>
							<?php if (isset($slug["error"])) { echo "<li>".$slug["error"]."</li>"; } ?>
							<?php if ($validLocale !== true) { echo "<li>".$validLocale."</li>"; } ?>
							<?php if ($allPeriodsValid !== true) {  echo "<li>".$allPeriodsValid."</li>"; } ?>
							<?php if ($validLimit !== true) {  echo "<li>".$validLimit."</li>"; } ?>
						</ul></div>

						<?php
			  	}
				// Deal with deleting an event from the database
			  }	elseif ( $action == 'delete' ) {

		  		$sql = $wpdb->prepare("DELETE FROM " . CLNDR_INSTANCES_TABLE . " WHERE instance_id='%d'",$instance_id);
		  		$wpdb->get_results($sql);

					?>
					<div class="updated"><p><?php _e('Instance succesfully deleted','clndr'); ?></p></div>
					<?php
		  	}
		  }
		}
	}

	// display page components
  ?>

  <div class="wrap">
  	<?php
		if ( $action == 'edit' || ($action == 'edit_save' && $CLNDR_input_errors)) 	{
  		?>
  		<h2><?php _e('Edit Instance','clndr'); ?></h2>
  		<?php
			if (!$instance_id) { ?>
				<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Invalid instance ID",'clndr'); ?></p></div>
				<?php
  		} else {
  			admin_CLNDR_INSTANCES_edit_form("edit_save", $instance_id);
  		}
  	} else {
  		?>
			<h2><?php _e('Manage Instances','clndr'); ?></h2>
  		<?php	admin_CLNDR_INSTANCES_display_instances(); ?>

  		<h2><?php _e('Add Instance','clndr'); ?></h2>
  		<?php admin_CLNDR_INSTANCES_edit_form("add");

  	}
  	?>
  </div>

  <?php

}

/*
 * Function to check if an instance name already exists, else returns valid slug
 *
 * @param		string		$name						the name of the instance to check
 * @param		int				$instance_id		the id of the current instance id, if any
 * @return	bool/string		false if name already exists, else string of the slug
*/
function admin_CLNDR_INSTANCES_verify_instance_name($name,$instance_id=false) {
	global $wpdb;

	if ($name == "") {
		return array("status"=>"error","error"=>__("Instance name cannot be empty","clndr"));
	}

	$instances = $wpdb->get_results("SELECT * FROM " . CLNDR_INSTANCES_TABLE, "OBJECT_K");

	if (!empty($instances)) {
		$names = array();
		$slugs = array();
		foreach ($instances as $key => $instance) {
			$names[$key] = $instance->instance_name;
			$slugs[$key] = $instance->instance_slug;
		}

		$keyInArray = array_search($name,$names);
		if ($keyInArray !== false && $keyInArray !== $instance_id) {
			return array("status"=>"error","error"=>__("Instance name already taken","clndr"));
		}

		$slug = sanitize_title($name);

		$keyInArray = array_search($slug,$slugs);
		if ($keyInArray !== false && $keyInArray !== $instance_id) {
			$slugTaken = true;
			$counter = 2;
			while ($slugTaken) {
				$newSlug = $slug."-".$counter;
				if (!in_array($newSlug,$slugs)) {
					$slugTaken = false;
					$slug = $newSlug;
				}
				$counter++;
			}
		}

		return array("status"=>"success","slug"=>$slug);

	}

}


/*
 * Function to display the actual edit instance form (includes add instance form)
 *
 * @param		string	$wp_CLNDR_mode					the mode of the form. recognises "add" or "edit_save"
 * @param		int			$wp_CLNDR_instance_id		the instnace id. defaults to false as add mode does not require one
*/
function admin_CLNDR_INSTANCES_edit_form($wp_CLNDR_mode='add', $wp_CLNDR_instance_id=false) {
	global $wpdb, $users_entries;
	$data = false;

	if (!empty($users_entries)) {
	  $data = $users_entries;
	} elseif ($wp_CLNDR_instance_id) {
		$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . CLNDR_INSTANCES_TABLE . " WHERE instance_id='%d' LIMIT 1", $wp_CLNDR_instance_id));
	}

	$wp_CLNDR_default_template = '
<!-- STANDARD CLNDR MARKUP -->
<div class="clndr-controls">
	<div class="current-month"><%= month %> <%= year %></div>
	<div class="clndr-nav clndr-clearfix">
		<div class="clndr-previous-button">‹</div>
		<div class="clndr-next-button">›</div>
	</div>
</div>
<div class="clndr-grid">
	<div class="days-of-the-week clndr-clearfix">
		<% _.each(daysOfTheWeek, function(day) { %>
		<div class="header-day"><%= day %></div>
		<% }); %>
	</div>
	<div class="days clndr-clearfix">
		<% _.each(days, function(day) { %>
		<div class="<%= day.classes %>" id="<%= day.id %>"><span class="day-number"><%= day.day %></span></div>
		<% }); %>
	</div>
</div>
<div class="event-listing">
	<div class="event-listing-title">Events</div>
	<% _.each(eventsThisMonth, function(event) { %>
		<% if (event.url) {  %><a target="_blank" href="<%= event.url %>" <% } else {  %><div <% }  %> class="event-item clndr-clearfix">
			<span class="event-item-date">
				<%= moment(event.start).format("D MMMM") %>
				<% if (event.end != event.start) {  %>
					 &ndash; <%= moment(event.end).format("D MMMM") %>
				<% } %>
			</span>
			<span class="event-item-name"><%= event.title %></span>
			<% if (event.time) {  %>
				<span class="event-item-time"><%= event.time %></span>
			<% } %>
			<% if (event.desc) {  %>
				<span class="event-item-desc"><%= event.desc %></span>
			<% } %>
		<% if (event.url) {  %></a><% } else {  %></div><% }  %>
	<% }); %>
</div>';

	$wp_CLNDR_default_options = '
doneRendering: function() {
	var day=1, week=1, thisCLNDR = $(this)[0]["element"];
	//make the background rows alternate colour
	thisCLNDR.find(".day").each(function() {
		if (day == 8) { day = 1; week++; }
		if (week % 2 === 0) { $(this).addClass("alternate-bg"); }
		day++;
	});
	//display a notice if there are no events for a month
	var thisMonthEvents = thisCLNDR.find(".event-item").length;
	if (thisMonthEvents == 0) {
		thisCLNDR.find(".event-listing").append(
			"<div style=\'text-align:center;\' class=\'event-item\'>No events found</div>"
		);
	}
},
weekOffset: 1';

	$wp_CLNDR_name = (isset($data->instance_name) ? $data->instance_name : "");
	$wp_CLNDR_template = (isset($data->instance_template) ? $data->instance_template : $wp_CLNDR_default_template);
	$wp_CLNDR_options = (isset($data->instance_options) ? $data->instance_options : $wp_CLNDR_default_options);
	$wp_CLNDR_nonce = $wp_CLNDR_mode != "add" ? "clndr-instances-".$wp_CLNDR_mode."_".$wp_CLNDR_instance_id : "clndr-instances-".$wp_CLNDR_mode;

	$wp_CLNDR_locales = admin_CLNDR_INSTANCES_get_locales();
	$wp_CLNDR_locale = (isset($data->instance_locale) ? $data->instance_locale : "en");

	$wp_CLNDR_time_periods = array(1 => __("Past events"), 2 => __("Current events"), 3 => __("Future events"));
	$wp_CLNDR_time_period = (isset($data->instance_periods) ? $data->instance_periods : "1,2,3");

	$wp_CLNDR_limit = (isset($data->instance_limit) && $data->instance_limit."" !== "0" ? $data->instance_limit : "");

	?>
	<form name="clndr_instances_form" class="wrap" method="post" action="<?php echo bloginfo('wpurl'); ?>/wp-admin/admin.php?page=clndr-instances" style="width:100%;max-width:1300px;">
		<input type="hidden" name="action" value="<?php echo ($wp_CLNDR_mode != "add" ? "edit_save" : $wp_CLNDR_mode); ?>">
		<input type="hidden" name="instance_id" value="<?php echo $wp_CLNDR_instance_id; ?>">
		<?php	wp_nonce_field($wp_CLNDR_nonce); ?>

		<div class="postbox">
			<div style="float: left; width: 98%; clear: both;" class="inside">
        <table cellpadding="5" cellspacing="5" width="100%">
          <tr>
						<td width="150">
							<legend for="instance_name" style="display:inline;"><?php _e('Name','clndr'); ?></legend> <span style="color:red;">*</span>
						</td>
						<td><input type="text" name="instance_name" class="input" style="width:100%;max-width:320px;" maxlength="60" value="<?php echo $wp_CLNDR_name; ?>" /></td>
          </tr>
          <tr>
						<td style="vertical-align:top;">
							<legend for="instance_template" style="display:inline;"><?php _e('HTML Template','clndr'); ?></legend><br />
							<br />
							<strong><?php _e("Reference","clndr"); ?></strong><br />
							<a href="https://github.com/kylestetz/CLNDR#introduction-you-write-the-markup" target="_blank"><?php _e("CLNDR.js Markup","clndr"); ?></a><br />
							<a href="http://momentjs.com/docs/#/parsing/string-format/" target="_blank"><?php _e("Moment.js Formatting","clndr"); ?></a>
						</td>
						<td><textarea name="instance_template" class="clndr-editor" data-language="application/x-jsp" style="width:100%;height:350px;"><?php echo $wp_CLNDR_template; ?></textarea></td>
          </tr>
					<tr>
						<td style="vertical-align:top;">
							<legend for="instance_options" style="display:inline;"><?php _e('JS Object Options','clndr'); ?></legend><br />
							<span style="display:block;padding-top:10px;font-size:0.9em;line-height:1.2;d">
								(<?php _e('No need to include event and template data, this is automatically populated','clndr'); ?>)<br />
							</span>
							<br />
							<strong><?php _e("Reference","clndr"); ?></strong><br />
							<a href="https://github.com/kylestetz/CLNDR#usage" target="_blank"><?php _e("CLNDR.js Options","clndr"); ?></a><br />
							<br />
							<strong><?php _e("Disclaimer","clndr"); ?></strong><br />
							<span style="font-size:0.9em;line-height:1.2;">
								<?php _e("It is recommended to only add Javascipt options here if you are a web developer, or generally know what you are doing. Incorrectly formatted input could hinder other Javascript functionality on your Wordpress website.","clndr"); ?>
							</span>
						</td>
						<td><textarea name="instance_options" class="clndr-editor" data-language="text/javascript"  style="width:100%;height:350px;"><?php echo $wp_CLNDR_options; ?></textarea></td>
          </tr>
					<tr>
						<td>
							<legend for="instance_locale" style="display:inline;"><?php _e('Locale','clndr'); ?></legend>
						</td>
						<td>
							<select name="instance_locale">
								<?php foreach ($wp_CLNDR_locales as $lo) { ?>
									<option value="<?php echo $lo; ?>" <?php selected($lo,$wp_CLNDR_locale) ?>><?php echo strtoupper($lo); ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:top;">
							<legend for="instance_periods[]" style="display:inline;"><?php _e('Show...','clndr'); ?></legend>
						</td>
						<td>
							<?php
							$cp = explode(",",$wp_CLNDR_time_period);
							foreach ($wp_CLNDR_time_periods as $key => $tp) { ?>
								<input type="checkbox" name="instance_periods[]" value="<?php echo $key; ?>" <?php if (in_array($key, $cp)) { echo 'checked="checked"'; } ?>><?php echo $tp; ?><br />
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<legend for="instance_limit" style="display:inline;"><?php _e('Max no. of events','clndr'); ?></legend>
						</td>
						<td><input type="text" style="width:50px;" name="instance_limit" value="<?php echo $wp_CLNDR_limit; ?>" /> &nbsp;&nbsp;(<?php _e('Leave blank for unlimited','clndr'); ?>) </td>
          </tr>
        </table>
			</div>
			<div style="clear:both;"></div>
		</div>
    <input type="submit" name="save" class="button button-primary button-large" value="<?php _e('Save','clndr'); ?>" />
	</form>
	<?php
}

function admin_CLNDR_INSTANCES_get_locales() {
	return array("en", "af", "ar-dz", "ar-ly", "ar-ma", "ar-sa", "ar-tn", "ar", "az", "be", "bg", "bg-x", "bn", "bo", "br", "bs", "ca", "cs", "cv", "cy", "da", "de-at", "de", "dv", "el", "en-au", "en-ca", "en-gb", "en-ie", "en-nz", "eo", "es-do", "es", "et", "eu", "fa", "fi", "fo", "fr-ca", "fr-ch", "fr", "fy", "gd", "gl", "he", "hi", "hr", "hu", "hy-am", "id", "is", "it", "ja", "jv", "ka", "kk", "km", "ko", "ky", "lb", "lo", "lt", "lv", "me", "mi", "mk", "ml", "mr", "ms-my", "ms", "my", "nb", "ne", "nl-be", "nl", "nn", "pa-in", "pl", "pt-br", "pt", "ro", "ru", "se", "si", "sk", "sl", "sq", "sr-cyrl", "sr", "ss", "sv", "sw", "ta", "te", "tet", "th", "tl-ph", "tlh", "tr", "tzl", "tzm-latn", "tzm", "uk", "uz", "vi", "x-pseudo", "yo", "zh-cn", "zh-hk", "zh-tw");
}

// Function to display a list of instances
function admin_CLNDR_INSTANCES_display_instances(){

	global $wpdb;
	$instances = $wpdb->get_results("SELECT * FROM " . CLNDR_INSTANCES_TABLE . " ORDER BY instance_id");

	if ( !empty($instances) ) {
	?>
		<p><?php _e("CLNDR shortcodes can be added into any page or post, as well as into the content of a 'Text' widget for sidebar or footer use.","clndr"); ?><p>

		<?php // if admin etc {

			$styles_url = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=clndr-styles";
			$styles_text = sprintf( wp_kses( __(
				'Individual CLNDR instances can be accessed directly from the <a href="%s">master stylesheet</a>. Their shortcode ID is applied <b>as a class</b> to the parent container, prepended by "clndr_".</p>', 'clndr'
			), array( 'a' => array( 'href' => array() ) ) ), esc_url( $styles_url ) );
			echo "<p>".$styles_text."</p>";

		 // }
		?>
  	<table class="widefat page fixed" width="100%" style="width:100%;max-width:900px;" cellpadding="3" cellspacing="3">
			<tr>
				<th class="manage-column" scope="col"><?php _e('Name','clndr') ?></th>
				<th class="manage-column" scope="col"><?php _e('Shortcode','clndr') ?></th>
				<th class="manage-column" scope="col" width="50">&nbsp;</th>
				<th class="manage-column" scope="col" width="50">&nbsp;</th>
			</tr>
			<?php
				$class = '';
				foreach ( $instances as $instance )	{
					$class = ($class == 'alternate') ? '' : 'alternate';

					$wp_CLNDR_edit_url = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=clndr-instances&amp;action=edit&amp;instance_id=" . $instance->instance_id;
					$wp_CLNDR_delete_url = wp_nonce_url (
						get_bloginfo('wpurl') . "/wp-admin/admin.php?page=clndr-instances&amp;action=delete&amp;instance_id=" . $instance->instance_id,
						"clndr-instances-delete_". $instance->instance_id );

					?>
					<tr class="<?php echo $class; ?>">
						<td><?php echo $instance->instance_name; ?></td>
						<td>[clndr id=<?php echo $instance->instance_slug; ?>]</td>
						<td><a href="<?php echo $wp_CLNDR_edit_url; ?>" class='edit'><?php echo __('Edit','clndr'); ?></a></td>
						<td><a href="<?php echo $wp_CLNDR_delete_url; ?>" class="delete" onclick="return confirm('<?php _e('Are you sure you want to delete this instance? Any active shortcodes will invalidate','clndr'); ?>')"><?php echo __('Delete','clndr'); ?></a></td>
					</tr>
				<?php
				}
			?>
		</table>
		<?php
	} else {
		?><p><?php _e("There are no instances in the database",'clndr');?></p><?php
	}
}

/*
 * Function to ensure an instance ID is valid
 *
 * @param		string/int	$id		the instance id
 * @return 	int/bool		the int of the instance if valid, otherwise false
*/
function admin_CLNDR_INSTANCES_verify_instance_id($id) {
	global $wpdb;
	if (!ctype_digit($id)) {
		return false;
	} else {
		$id = intval($id);
		$row = $wpdb->get_row($wpdb->prepare("SELECT instance_id FROM " . CLNDR_INSTANCES_TABLE . " WHERE instance_id=%d", $id));
		return ($row ? $id : false);
	}
}


/*
 * END OF FUNCTIONS FOR ADMIN INSTANCES PAGE
 * START OF FUNCTIONS FOR ADMIN EVENTS PAGE
*/

// Function to render the manage events page and to deal with user input
function admin_CLNDR_EVENTS() {

	admin_CLNDR_EVENTS_load_pikaday();

  global $wpdb, $users_entries;

	$CLNDR_input_errors = false;

  // Make sure we are collecting the variables we need to select years and months
  $action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
  $event_id = !empty($_REQUEST['event_id']) ? $_REQUEST['event_id'] : '';
	$event_id = admin_CLNDR_EVENTS_verify_event_id($event_id);

	$title = !empty($_REQUEST['event_title']) ? $_REQUEST['event_title'] : '';
	$desc = !empty($_REQUEST['event_desc']) ? $_REQUEST['event_desc'] : '';
	$begin = !empty($_REQUEST['event_begin']) ? $_REQUEST['event_begin'] : '';
	$end = !empty($_REQUEST['event_end']) ? $_REQUEST['event_end'] : '';
	$time = !empty($_REQUEST['event_time']) ? $_REQUEST['event_time'] : '';
	$link = !empty($_REQUEST['event_link']) ? $_REQUEST['event_link'] : '';

  if (!empty($action)) {

		//if the user is tring to do something
		if ($action == "add" || $action == "edit_save" || $action == "delete") {

			$nonce_end = ($action == "add" ? "" : "_".$event_id);

			if (($action == "edit_save" || $action == "delete") && !$event_id) { ?>
				<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Invalid event ID",'clndr'); ?></p></div>
				<?php
			}
	  	elseif (wp_verify_nonce($_REQUEST['_wpnonce'],'clndr-events-'.$action.$nonce_end) == false) {	?>
 		  	<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Security check failure, please try again",'clndr'); ?></p></div>
 				<?php
 			} else {
				if ($action == "add" || $action == "edit_save") {
					// wp_CLNDR_verify_event returns errors if there are any, otherwise returns the santized data ready for the db
					$verify = admin_CLNDR_EVENTS_validate_event_fields($title,$desc,$begin,$end,$time,$link);

			  	if ($verify["status"] == "success") {

						$sql_main = CLNDR_EVENTS_TABLE . " SET event_title='%s', event_desc='%s', event_begin='%s', event_end='%s', event_time='%s', event_link='%s'";
						$db_vars = $verify["db_vars"];

						if ($action == "add") {

				    	$sql = $wpdb->prepare(
								"INSERT INTO " . $sql_main,
								$db_vars["title"],$db_vars["desc"],$db_vars["begin"],$db_vars["end"],$db_vars["time"],$db_vars["link"]
							);
							$wpdb->get_results($sql);
							?>

							<div class="updated"><p><?php _e('Event succesfully added. It will now show in your calendar','clndr'); ?></p></div>

							<?php
						} else {

							$sql = $wpdb->prepare(
								"UPDATE " . $sql_main . "  WHERE event_id='%d'",
								$db_vars["title"],$db_vars["desc"],$db_vars["begin"],$db_vars["end"],$db_vars["time"],$db_vars["link"],$event_id
							);
							$wpdb->get_results($sql);
							?>

							<div class="updated"><p><?php _e('Event succesfully edited','clndr'); ?></p></div>

							<?php
						}

				    do_action('add_calendar_entry', $action);

				  }	elseif ($verify["status"] == "errors") {
				    // The form is going to be rejected due to field validation issues, so we preserve the users entries here
						$users_entries = new stdClass();
				    $users_entries->event_title = admin_CLNDR_sanitize_input_field($title);
				    $users_entries->event_desc = admin_CLNDR_sanitize_input_field($desc,true);
				    $users_entries->event_begin = admin_CLNDR_sanitize_input_field($begin);
				    $users_entries->event_end = admin_CLNDR_sanitize_input_field($end);
				    $users_entries->event_time = admin_CLNDR_sanitize_input_field($time);
				    $users_entries->event_link = admin_CLNDR_sanitize_input_field($link);
						$CLNDR_input_errors = true;
						?>

						<div class="error">
							<p><strong><?php _e("Please address the below error(s) ",'clndr'); ?></strong></p>
							<ul>
								<?php foreach ($verify["errors"] as $e) { ?>
									<li><?php echo $e; ?></li>
								<?php } ?>
							</ul>
						</div>

						<?php
			  	}
				// Deal with deleting an event from the database
			  }	elseif ( $action == 'delete' ) {

		  		$sql = $wpdb->prepare("DELETE FROM " . CLNDR_EVENTS_TABLE . " WHERE event_id='%d'",$event_id);
		  		$wpdb->get_results($sql);

					?>
					<div class="updated"><p><?php _e('Event succesfully deleted','clndr'); ?></p></div>
					<?php
		  	}
		  }
		}
	}

  // display page components
  ?>

  <div class="wrap">
  	<?php
		if ( $action == 'edit' || ($action == 'edit_save' && $CLNDR_input_errors)) 	{
  		?>
  		<h2><?php _e('Edit Event','clndr'); ?></h2>
  		<?php
			if (!$event_id) { ?>
				<div class="error"><p><strong><?php _e('Error','clndr'); ?>:</strong> <?php _e("Invalid event ID",'clndr'); ?></p></div>
				<?php
  		} else {
  			admin_CLNDR_EVENTS_edit_form("edit_save", $event_id);
  		}
  	} else {
  		?>
  		<h2><?php _e('Add Event','clndr'); ?></h2>
  		<?php admin_CLNDR_EVENTS_edit_form("add"); ?>

  		<h2><?php _e('Manage Events','clndr'); ?></h2>
  		<?php	admin_CLNDR_EVENTS_display_events();
  	}
  	?>
  </div>

  <?php

}

// Function to load the datepicker
function admin_CLNDR_EVENTS_load_pikaday() {

	//pikaday css loaded in admin_CLNDR_scripts()
	wp_register_script( 'moment', CLNDR_DIR_URL . 'libs/clndr/moment-with-locales.min.js');
	wp_register_script( 'pikaday', CLNDR_DIR_URL . 'libs/pikaday/pikaday.min.js');
	wp_enqueue_script( 'moment' );
	wp_enqueue_script( 'pikaday' );

	add_action("admin_print_footer_scripts", function() {
		?>
		<script>
			var begin_picker = new Pikaday({
				field: document.getElementById('event_begin'),
				firstDay: 1,
				format: "YYYY-MM-DD",
				onSelect: function() {
					var begin_date = begin_picker.getDate();
					end_picker.setMinDate(begin_date);
					if (document.getElementById('event_end').value == "") {
						end_picker.setDate(begin_date);
					}
				}
			});
			var end_picker = new Pikaday({
				field: document.getElementById('event_end'),
				firstDay: 1,
				format: "YYYY-MM-DD"
			});
		</script>
		<?php
	},100);
}

// Function to display a list of events
function admin_CLNDR_EVENTS_display_events(){

	global $wpdb;
	$events = $wpdb->get_results("SELECT * FROM " . CLNDR_EVENTS_TABLE . " ORDER BY event_begin DESC");

	if ( !empty($events) ) {
	?>
  	<table class="widefat page fixed" width="100%" style="width:100%;max-width:1300px;" cellpadding="3" cellspacing="3">
			<tr>
				<th class="manage-column" scope="col"><?php _e('Title','clndr') ?></th>
				<th class="manage-column" scope="col"><?php _e('Start Date','clndr') ?></th>
				<th class="manage-column" scope="col"><?php _e('End Date','clndr') ?></th>
		    <th class="manage-column" scope="col"><?php _e('Time','clndr') ?></th>
				<th class="manage-column" scope="col" width="50">&nbsp;</th>
				<th class="manage-column" scope="col" width="50">&nbsp;</th>
			</tr>
			<?php
				$class = '';
				foreach ( $events as $event )	{
					$class = ($class == 'alternate') ? '' : 'alternate';

					$wp_CLNDR_edit_url = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=clndr&amp;action=edit&amp;event_id=" . $event->event_id;
					$wp_CLNDR_delete_url = wp_nonce_url (
						get_bloginfo('wpurl') . "/wp-admin/admin.php?page=clndr&amp;action=delete&amp;event_id=" . $event->event_id,
						"clndr-events-delete_". $event->event_id );

					?>
					<tr class="<?php echo $class; ?>">
						<td><?php echo $event->event_title; ?></td>
						<td><?php echo $event->event_begin; ?></td>
						<td><?php echo $event->event_end; ?></td>
						<td><?php echo $event->event_time; ?></td>
						<td><a href="<?php echo $wp_CLNDR_edit_url; ?>" class='edit'><?php echo __('Edit','clndr'); ?></a></td>
						<td><a href="<?php echo $wp_CLNDR_delete_url; ?>" class="delete" onclick="return confirm('<?php _e('Are you sure you want to delete this event?','clndr'); ?>')"><?php echo __('Delete','clndr'); ?></a></td>
					</tr>
				<?php
				}
			?>
		</table>
		<?php
	} else {
		_e("There are no events in the database",'clndr');
	}
}

/*
 * Function to display the actual edit event form (includes add event form)
 *
 * @param		string	$wp_CLNDR_mode			the mode of the form. recognises "add" or "edit_save"
 * @param		int			$wp_CLNDR_event_id	the event id. defaults to false as add mode does not require one
*/
function admin_CLNDR_EVENTS_edit_form($wp_CLNDR_mode='add', $wp_CLNDR_event_id=false) {
	global $wpdb, $users_entries;
	$data = false;

	if (!empty($users_entries)) {
	  $data = $users_entries;
	} elseif ($wp_CLNDR_event_id) {
		$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . CLNDR_EVENTS_TABLE . " WHERE event_id='%d' LIMIT 1", $wp_CLNDR_event_id));
	}

	$wp_CLNDR_title = (isset($data->event_title) ? $data->event_title : "");
	$wp_CLNDR_desc = (isset($data->event_desc) ? $data->event_desc : "");
	$wp_CLNDR_link = (isset($data->event_link) ? $data->event_link : "");
	$wp_CLNDR_start = (isset($data->event_begin) ? $data->event_begin : "");
	$wp_CLNDR_end = (isset($data->event_end) ? $data->event_end : "" );
	$wp_CLNDR_time = (isset($data->event_time) ? $data->event_time : "");
	$wp_CLNDR_nonce = $wp_CLNDR_mode != "add" ? "clndr-events-".$wp_CLNDR_mode."_".$wp_CLNDR_event_id : "clndr-events-".$wp_CLNDR_mode;

	?>
	<form name="clndr_event_form" class="wrap" method="post" action="<?php echo bloginfo('wpurl'); ?>/wp-admin/admin.php?page=clndr">
		<input type="hidden" name="action" value="<?php echo ($wp_CLNDR_mode != "add" ? "edit_save" : $wp_CLNDR_mode); ?>">
		<input type="hidden" name="event_id" value="<?php echo $wp_CLNDR_event_id; ?>">
		<?php	wp_nonce_field($wp_CLNDR_nonce); ?>

		<div class="postbox">
			<div style="float: left; width: 98%; clear: both;" class="inside">
        <table cellpadding="5" cellspacing="5" style="width:100%;">
          <tr>
						<td style="width:130px;"><legend for="event_title" style="display:inline;"><?php _e('Event Title','clndr'); ?></legend> <span style="color:red;">*</span></td>
						<td><input type="text" name="event_title" class="input"  style="width:100%;max-width:320px;" maxlength="50" value="<?php echo $wp_CLNDR_title; ?>" /></td>
          </tr>
          <tr>
						<td style="vertical-align:top;">
								<legend for="event_desc" style="display:inline;"><?php _e('Event Description','clndr'); ?></legend>
								<div style="font-size:0.9em;line-height:1.2;padding-top:5px;">
									<?php _e("Allowable HTML tags: ","clndr"); ?>&lt;b&gt;&lt;i&gt;&lt;u&gt;&lt;img&gt;
								</div>
						</td>
						<td><textarea name="event_desc" class="input" rows="5" style="width:100%;max-width:500px;"><?php echo str_replace("<br>", "\n", $wp_CLNDR_desc); ?></textarea></td>
          </tr>
          <tr>
						<td><legend for="event_begin" style="display:inline;"><?php _e('Start Date','clndr'); ?></legend> <span style="color:red;">*</span></td>
            <td><input id="event_begin" type="text" name="event_begin" class="input datepicker"  style="width:100%;max-width:100px;"	value="<?php echo $wp_CLNDR_start; ?>" /></td>
          </tr>
					<tr>
						<td><legend for="event_end" style="display:inline;"><?php _e('End Date','clndr'); ?></legend> <span style="color:red;">*</span></td>
            <td><input id="event_end" type="text" name="event_end" class="input datepicker" style="width:100%;max-width:100px;" value="<?php echo $wp_CLNDR_end; ?>" /></td>
          </tr>
					<tr>
						<td><legend for="event_time" style="display:inline;"><?php _e('Time','clndr'); ?></legend></td>
						<td><input type="text" name="event_time" class="input" style="width:100%;max-width:200px;" maxlength="100"	value="<?php echo $wp_CLNDR_time; ?>" /></td>
					</tr>
					<tr>
						<td><legend for="event_link" style="display:inline;"><?php _e('Event Link ','clndr'); ?></legend></td>
            <td><input type="text" name="event_link" class="input"  style="width:100%;max-width:320px;" maxlength="2048"	value="<?php echo $wp_CLNDR_link; ?>" /></td>
          </tr>
        </table>
			</div>
			<div style="clear:both;"></div>
		</div>
    <input type="submit" name="save" class="button button-primary button-large" value="<?php _e('Save','clndr'); ?>" />
	</form>
	<?php
}

/*
 * Function to ensure an event ID is valid
 *
 * @param		string/int	$id		the event id
 * @return 	int/bool		the int of the event if valid, otherwise false
*/
function admin_CLNDR_EVENTS_verify_event_id($id) {
	global $wpdb;
	if (!ctype_digit($id)) {
		return false;
	} else {
		$id = intval($id);
		$row = $wpdb->get_row($wpdb->prepare("SELECT event_id FROM " . CLNDR_EVENTS_TABLE . " WHERE event_id=%d", $id));
		return ($row ? $id : false);
	}
}

/*
 * Function to validate event fields
 *
 * @param		string	$title	the event title
 * @param		string	$desc		the event desc
 * @param		string	$begin	the event begin date
 * @param		string	$end		the event end date
 * @param		string	$time		the event time
 * @param		string	$link		the event link
 * @return 	array(status,array(santized fields OR errors))
*/
function admin_CLNDR_EVENTS_validate_event_fields($title,$desc,$begin,$end,$time,$link) {

	$errors = array();

	if ($title == "") {
		$errors[] = __("The title field is empty",'clndr');
	} elseif (strlen($title) > 50) {
		$errors[] = __("The title field can only contain 50 characters or less",'clndr');
	} else {
		$title = admin_CLNDR_sanitize_input_field($title);
	}

	$beginParts = explode("-",$begin);
	if (count($beginParts) != 3) {
		$errors[] = __("The start date is empty, or not in the correct format",'clndr');
	} else {
		$beginDateIsValid = checkdate($beginParts[1], $beginParts[2], $beginParts[0]);
		if (!$beginDateIsValid) {
			$errors[] = __("The start date is not valid",'clndr');
		}
	}


	$endParts = explode("-",$end);
	if (count($endParts) != 3) {
		$errors[] = __("The end date is empty, or not in the correct format",'clndr');
	} else {
		$endDateIsValid = checkdate($endParts[1], $endParts[2], $endParts[0]);
		if (!$endDateIsValid) {
			$errors[] = __("The end date is not valid",'clndr');
		} elseif ($beginDateIsValid) {
			if (mktime(0,0,0,$endParts[1], $endParts[2], $endParts[0]) < mktime(0,0,0,$beginParts[1], $beginParts[2], $beginParts[0])) {
				$errors[] = __("The end date cannot be before the start date",'clndr');
			}
		}
	}

	if (strlen($time) > 100) {
		$errors[] = __("The time field can only contain 100 characters or less",'clndr');
	} else {
		$time = admin_CLNDR_sanitize_input_field($time);
	}

	if (strlen($link) > 2048) {
		$errors[] = __("The link field can only contain 2,048 characters or less",'clndr');
	} else {
		$link = admin_CLNDR_sanitize_input_field($link);
	}

	if (empty($errors)) {
		$desc = admin_CLNDR_sanitize_input_field($desc,true);
		return array("status"=>"success","db_vars"=>array("title"=>$title,"desc"=>$desc,"begin"=>$begin,"end"=>$end,"time"=>$time,"link"=>$link));
	} else {
		return array("status"=>"errors","errors"=>$errors);
	}
}

/*
 * END OF FUNCTIONS FOR ADMIN EVENTS PAGE
 * START OF FUNCTIONS FOR ADMIN STYLES PAGE
*/

// Function to show/manage the admin styles page
function admin_CLNDR_STYLES() {

  admin_CLNDR_load_codemirror();
  $file = plugin_dir_path( __FILE__ ) . "css/clndr-styles.css";
	?>
	<div class="wrap">

		<h2><?php _e('Edit Master Stylesheet','clndr'); ?></h2>
	  <h4><?php echo "(" . $file . ")" ?></h4>

		<?php

	  if (isset($_GET["page"]) && isset($_POST["action"])) {
	    if ($_GET["page"] == "clndr-styles" && $_POST["action"] == "update") {

	      if ( is_writeable( $file ) ) {
	        $f = fopen( $file, 'w+' );
	        if ( $f !== false ) {
						//normalize the line endings
						$newfile = str_replace( array( "\r\n", "\r" ), "\n", $_POST["newcontent"] ) ;
	          fwrite( $f, wp_unslash( $newfile ) );
	          fclose( $f );
	          echo '<div class="updated" style="margin-bottom:20px;"><p>' . __("Successfully updated the stylesheet","clndr") . '</p></div>';
	        }
	      } else {
					echo '<div class="error" style="margin-bottom:20px;"><p>' . __("Error. The stylesheet does not have the correct permissions to be writable","clndr") . '</p></div>';
	      }
	    }
	  }

	  $f = fopen($file, 'r');
	  $content = ($f ? esc_textarea(fread($f, filesize($file))) : "");

	  ?>

	  <form name="template" action="admin.php?page=clndr-styles" method="post">
			<div>
	        <textarea class="clndr-editor" data-language="text/css" name="newcontent" style="width:100%;height:550px;"><?php echo $content; ?></textarea>
			    <input type="hidden" name="action" value="update" />
			</div>
	    <br>
			<div>
	      <?php
	    	if (is_writeable( $file )) {
	    		?>
	        <input type="submit" name="submit" class="button button-primary button-large" value="<?php _e('Update File','clndr'); ?>" />
	        <?php
	    	} else {
					$codex_url = "https://codex.wordpress.org/Changing_File_Permissions";
					$error_text = sprintf( wp_kses( __(
						'You need to make this file writable before you can save your changes. See <a href="%s">the Codex</a> for more information.', 'clndr'
					), array( 'a' => array( 'href' => array() ) ) ), esc_url( $codex_url ) );
					echo "<p><em>".$error_text."</em></p>";
	      } ?>
	    </div>
	  </form>
	</div>
	<?php
}

// Function to render the textarea as a code editor
function admin_CLNDR_load_codemirror() {
	//codemirror css loaded in admin_CLNDR_scripts()
  wp_register_script( 'codemirror', CLNDR_DIR_URL . 'libs/codemirror/codemirror.min.js');
	wp_register_script( 'codemirror/addon/fold/xml-fold.js', CLNDR_DIR_URL . 'libs/codemirror/addon/fold/xml-fold.js');
	wp_register_script( 'codemirror/addon/edit/matchtags.js', CLNDR_DIR_URL . 'libs/codemirror/addon/edit/matchtags.js');
	wp_register_script( 'codemirror/addon/edit/matchbrackets.js', CLNDR_DIR_URL . 'libs/codemirror/addon/edit/matchbrackets.js');
	wp_register_script( 'codemirror/javascript.js', CLNDR_DIR_URL . 'libs/codemirror/mode/javascript/javascript.js');
  wp_register_script( 'codemirror/css.js', CLNDR_DIR_URL . 'libs/codemirror/mode/css/css.js');
  wp_register_script( 'codemirror/xml.js', CLNDR_DIR_URL . 'libs/codemirror/mode/xml/xml.js');
  wp_register_script( 'codemirror/htmlmixed.js', CLNDR_DIR_URL . 'libs/codemirror/mode/htmlmixed/htmlmixed.js');
	wp_register_script( 'codemirror/addon/mode/multiplex.js', CLNDR_DIR_URL . 'libs/codemirror/addon/mode/multiplex.js');
  wp_register_script( 'codemirror/htmlembedded.js', CLNDR_DIR_URL . 'libs/codemirror/mode/htmlembedded/htmlembedded.js');
  wp_register_script( 'codemirror/init.js', CLNDR_DIR_URL . 'libs/codemirror/init.js');
  wp_enqueue_script( 'codemirror' );
  wp_enqueue_script( 'codemirror/addon/fold/xml-fold.js' );
  wp_enqueue_script( 'codemirror/addon/edit/matchtags.js' );
  wp_enqueue_script( 'codemirror/addon/edit/matchbrackets.js' );
	wp_enqueue_script( 'codemirror/javascript.js' );
  wp_enqueue_script( 'codemirror/css.js' );
  wp_enqueue_script( 'codemirror/xml.js' );
  wp_enqueue_script( 'codemirror/htmlmixed.js' );
	wp_enqueue_script( 'codemirror/addon/mode/multiplex.js' );
  wp_enqueue_script( 'codemirror/htmlembedded.js' );
  wp_enqueue_script( 'codemirror/init.js', false, array("codemirror","jquery") );
}

/*
 * Function to santize input field content
 *
 * @param		string		$string			the input string to sanitize
 * @param		boolean		$textarea		is the input string from a textarea
 * @return 	string		the sanitized string
*/
function admin_CLNDR_sanitize_input_field($string,$textarea=false) {

	$string = stripslashes($string);

	if ($textarea) {
		$string = strip_tags($string,"<b><i><u><img>");
		$string = str_replace( "'", '&#039;', $string );
		$string = str_replace( "\n", '<br>', $string );
		$string = trim( preg_replace('/[\r\n\t ]+/', ' ', $string) );
	} else {
		$string = htmlspecialchars(sanitize_text_field($string),ENT_QUOTES);
	}

	return $string;

};

// Function to enqueue scripts for our admin pages
function admin_CLNDR_stylesheets() {
	//our JS is enqueued inline and so placed in the footer and only loaded when needed.
	//The CSS has go in the head and it's inexpensive, so we'll load it on every page
	//as it's the easiest option
	wp_register_style( 'pikaday.css', CLNDR_DIR_URL . 'libs/pikaday/pikaday.css');
	wp_enqueue_style( 'pikaday.css' );
	wp_register_style( 'codemirror.css', CLNDR_DIR_URL . 'libs/codemirror/codemirror.css');
	wp_enqueue_style( 'codemirror.css' );
}


/*
 * END OF FUNCTIONS FOR ADMIN STYLES PAGE
*/
