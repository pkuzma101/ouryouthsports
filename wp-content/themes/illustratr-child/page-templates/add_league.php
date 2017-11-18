<?php
/**
 * Template Name: Add League Page Template
 *
 * @package illustratr
 */

require(ABSPATH . "wp-admin" . '/includes/image.php');
require(ABSPATH . "wp-admin" . '/includes/file.php');
require(ABSPATH . "wp-admin" . '/includes/media.php');

// // get all regions
$regions = $wpdb->get_results("SELECT region_name FROM regions ORDER BY region_name");

// get all sports
$sports = $wpdb->get_results("SELECT sport_name FROM sports");

// submit new league to database
if (isset($_POST['submit_league'])) {
  if (empty($_POST['league_name']) || $_POST['sport'] == 'nil' || $_POST['region'] == 'nil') {
    echo '<p class="alert alert-danger" role="alert">Program Name, Sport, and Region must be filled</p>'; 
  } 
  else {
    if ($_FILES['logo']['type'] != '') {
      $logo_type = $_FILES['logo']['type'];
      if ($logo_type == 'image/jpg' || $logo_type == 'image/png' || $logo_type == 'image/gif' || $logo_type == 'image/jpeg') {
        foreach ($_FILES as $file => $array) {
          if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
              return "upload error : " . $_FILES[$file]['error'];
          }
          $attach_id = media_handle_upload($file, 0);
        }
      }
      else {
        echo '<p class="alert alert-danger" role="alert">Logo must be jpg, jpeg, png, or gif</p>'; 
        die;
      }
    }
    // create entry for league_logo
    $logo_name = basename($_FILES['logo']['name']);
    $logo_type = $_FILES['logo']['type'];
    $logo_path = '/ouryouthsports/wp-content/uploads';

    $year = date("2017");
    // $month = date("m");
    $month = "05";

    $new_logo_path = $logo_path . '/' . $year . '/' . $month . '/' . $logo_name;

    $old_name = htmlspecialchars(strip_tags($_POST['league_name']));
    $name = str_ireplace("\\", "", $old_name);
    
    $user_id = get_current_user_id();

    $program_type = htmlspecialchars(strip_tags($_POST['program_type']));

    // switch case that determines which table data is entered to
    switch ($program_type) {
      case "league":
        $wpdb->insert('leagues', array(
          'league_name'     => $name,
          'sport'           => htmlspecialchars(strip_tags($_POST['sport'])),
          'league_region'   => htmlspecialchars(strip_tags($_POST['region'])),
          'link_to_site'    => htmlspecialchars(strip_tags($_POST['link'])),
          'league_phone'    => htmlspecialchars(strip_tags($_POST['league_phone'])),
          'league_fax'      => htmlspecialchars(strip_tags($_POST['league_fax'])),
          'league_contact'  => htmlspecialchars(strip_tags($_POST['league_contact'])),
          'league_email'    => htmlspecialchars(strip_tags($_POST['league_email'])),
          'league_logo'     => $new_logo_path,
          'user_id'         => $user_id,
          'approved'        => 0
        ));
        break;
      case "camp":
        $wpdb->insert('camps', array(
          'camp_name'       => $name,
          'sport'           => htmlspecialchars(strip_tags($_POST['sport'])),
          'camp_region'     => htmlspecialchars(strip_tags($_POST['region'])),
          'start_date'      => htmlspecialchars(strip_tags($_POST['start_date'])),
          'end_date'        => htmlspecialchars(strip_tags($_POST['end_date'])),
          'link_to_site'    => htmlspecialchars(strip_tags($_POST['link'])),
          'camp_phone'      => htmlspecialchars(strip_tags($_POST['league_phone'])),
          'camp_fax'        => htmlspecialchars(strip_tags($_POST['league_fax'])),
          'camp_contact'    => htmlspecialchars(strip_tags($_POST['league_contact'])),
          'camp_email'      => htmlspecialchars(strip_tags($_POST['league_email'])),
          'camp_logo'       => $new_logo_path,
          'user_id'         => $user_id,
          'approved'        => 0
        ));

        $wpdb->insert('wp_clndr_events', array(
          'event_begin'     => htmlspecialchars(strip_tags($_POST['start_date'])),
          'event_end'       => htmlspecialchars(strip_tags($_POST['end_date'])),
          'event_title'     => $name,
          'event_link'      => htmlspecialchars(strip_tags($_POST['link'])),
          'event_desc'      => htmlspecialchars(strip_tags($_POST['phone']))
        ));
        break;
      case "tournament":
        $wpdb->insert('tournaments', array(
          't_name'       => $name,
          'sport'        => htmlspecialchars(strip_tags($_POST['sport'])),
          't_region'     => htmlspecialchars(strip_tags($_POST['region'])),
          'start_date'   => htmlspecialchars(strip_tags($_POST['start_date'])),
          'end_date'     => htmlspecialchars(strip_tags($_POST['end_date'])),
          'link_to_site' => htmlspecialchars(strip_tags($_POST['link'])),
          't_phone'      => htmlspecialchars(strip_tags($_POST['league_phone'])),
          't_fax'        => htmlspecialchars(strip_tags($_POST['league_fax'])),
          't_contact'    => htmlspecialchars(strip_tags($_POST['league_contact'])),
          't_email'      => htmlspecialchars(strip_tags($_POST['league_email'])),
          't_logo'       => $new_logo_path,
          'user_id'      => $user_id,
          'approved'     => 0
        ));

        $wpdb->insert('wp_clndr_events', array(
          'event_begin'     => htmlspecialchars(strip_tags($_POST['start_date'])),
          'event_end'       => htmlspecialchars(strip_tags($_POST['end_date'])),
          'event_title'     => $name,
          'event_link'      => htmlspecialchars(strip_tags($_POST['link'])),
          'event_desc'      => htmlspecialchars(strip_tags($_POST['phone']))
        ));
        break;
      case "training":
        $wpdb->insert('trainings', array(
          'train_name'       => $name,
          'sport'           => htmlspecialchars(strip_tags($_POST['sport'])),
          'train_region'     => htmlspecialchars(strip_tags($_POST['region'])),
          'start_date'      => htmlspecialchars(strip_tags($_POST['start_date'])),
          'end_date'        => htmlspecialchars(strip_tags($_POST['end_date'])),
          'link_to_site'    => htmlspecialchars(strip_tags($_POST['link'])),
          'train_phone'      => htmlspecialchars(strip_tags($_POST['league_phone'])),
          'train_fax'        => htmlspecialchars(strip_tags($_POST['league_fax'])),
          'train_contact'    => htmlspecialchars(strip_tags($_POST['league_contact'])),
          'train_email'      => htmlspecialchars(strip_tags($_POST['league_email'])),
          'train_logo'       => $new_logo_path,
          'user_id'         => $user_id,
          'approved'        => 0
        ));
        break;
      default:
        echo "Error";
    }
    echo '<p class="alert alert-success">Program added!  Now waiting for Administrator approval!</p>';
  }
}

get_header(); ?>

<style>
  section {
    min-height: 1200px;
  }
  @media screen and (max-width: 767px) {
    section {
      min-height: 800px;
    }
  }
  section#add_league_page h2 {
    text-align: center;
    color: white;
  }
  article#form_article {
    width: 70%;
  }
  @media screen and (max-width: 767px) {
    article#form_article {
      width: 100%;
    }
  }
  article#form_article form#new_league_form {
    padding: 30px;
    background-color: #a8a2a3;
  }
   @media screen and (max-width: 767px) {
    article#form_article form#new_league_form {
      padding: 10px;
    }
  }
  article#form_article form#new_league_form label {
    color: black;
  }
  span.required_mark {
    color: red;
  }
</style>

<section id="add_league_page" class="container-fluid">
  <h2>Add a New Program</h2>
  <article class="container-fluid" id="form_article">
    <div style="color:white;"><span class="required_mark">*</span> = required field</div>
    <form method="post" id="new_league_form" enctype="multipart/form-data">
      <div class="form-group">
        <label for="program_type">Type of Program<span class="required_mark">*</span></label>
        <select class="form-control" id="program_type" name="program_type" required="true">
          <option value="nil">Select Program Type</option>
          <option value="league">League</option>
          <option value="camp">Camp</option>
          <option value="tournament">Tournament</option>
          <option value="training">Training</option>
        </select>
      </div>
      <div class="form-group">
        <label for="league_name">Program Name<span class="required_mark">*</span></label>
        <input type="text" class="form-control" id="league_name" name="league_name" required="true">
      </div>
      <div class="form-group">
        <label for="sport">Sport<span class="required_mark">*</span></label>
        <select name="sport" id="sport" class="form-control">
          <option value="nil">Select a Sport</option>
          <? foreach($sports as $sport): ?>
          <option value="<?php echo $sport->sport_name; ?>"><?php echo $sport->sport_name; ?></option>
          <? endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label for="region">Region<span class="required_mark">*</span></label>
        <select name="region" id="region" class="form-control">
          <option value="nil">Select a Region</option>
          <? foreach($regions as $region): ?>
          <option value="<?php echo $region->region_name; ?>"><?php echo $region->region_name; ?></option>
          <? endforeach ?>
        </select>
      </div>
      <div class="form-group date_div" style="display:none;">
        <label for="start_date">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date">
      </div>
      <div class="form-group date_div" style="display:none;">
        <label for="end_date">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
      </div>
      <div class="form-group">
        <label for="logo">Logo</label>
        <input type="file" class="form-control" id="logo" name="logo">
      </div>
      <div class="form-group">
        <label for="link">Link to Website</label>
        <input type="url" class="form-control" id="link" name="link">
      </div>
      <div class="form-group">
        <label for="league_phne">Phone</label>
        <input type="tel" class="form-control" id="league_phone" name="league_phone">
      </div>
      <div class="form-group">
        <label for="league_fax">Fax</label>
        <input type="tel" class="form-control" id="league_fax" name="league_fax">
      </div>
      <div class="form-group">
        <label for="league_email">Email</label>
        <input type="email" class="form-control" id="league_email" name="league_email">
      </div>
      <div class="form-group">
        <label for="league_contact">Contact</label>
        <input type="text" class="form-control" id="league_contact" name="league_contact">
      </div>
      <button type="submit" class="btn btn-primary" id="submit_league" name="submit_league">Submit</button>
    </form>
  </article>
</section>
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {
      $('#program_type').change(function() {
        if ($(this).val() == 'camp' || $(this).val() == 'tournament' || $(this).val() == 'training') {
          $('div.date_div').css("display", "block");
        }
        else {
          $('div.date_div').css("display", "none");
        }
      });
    });
  })(jQuery);
</script>