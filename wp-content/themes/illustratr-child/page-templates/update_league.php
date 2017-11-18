<?php
/**
 * Template Name: Update League Template
 *
 * @package illustratr
 */

require(ABSPATH . "wp-admin" . '/includes/image.php');
require(ABSPATH . "wp-admin" . '/includes/file.php');
require(ABSPATH . "wp-admin" . '/includes/media.php');

$league_id = $_GET['id'];
$event_type = $_GET['event_type'];
$event_switch = "";
switch($event_type) {
  case "camps":
    $event_switch = 'camp_id';
    $league = $wpdb->get_results("SELECT * FROM " . $event_type . " WHERE " . $event_switch . " = " . $league_id);

    foreach ($league as $v) {
      $l_name = $v->camp_name;
      $l_region = $v->camp_region;
      $l_sport = $v->sport;
      $l_start_date = $v->start_date;
      $l_end_date = $v->end_date;
      $l_link = $v->link_to_site;
      $l_phone = $v->camp_phone;
      $l_fax = $v->camp_fax;
      $l_contact = $v->camp_contact;
      $l_email = $v->camp_email;
    }
    break;
  case "tournaments":
    $event_switch = 't_id';
    $league = $wpdb->get_results("SELECT * FROM " . $event_type . " WHERE " . $event_switch . " = " . $league_id);

    foreach ($league as $v) {
      $l_name = $v->t_name;
      $l_region = $v->t_region;
      $l_sport = $v->sport;
      $l_start_date = $v->start_date;
      $l_end_date = $v->end_date;
      $l_link = $v->link_to_site;
      $l_phone = $v->t_phone;
      $l_fax = $v->t_fax;
      $l_contact = $v->t_contact;
      $l_email = $v->t_email;
    }
    break;
  case "trainings":
    $event_switch = 'train_id';
    $league = $wpdb->get_results("SELECT * FROM " . $event_type . " WHERE " . $event_switch . " = " . $league_id);

    foreach ($league as $v) {
      $l_name = $v->train_name;
      $l_region = $v->train_region;
      $l_sport = $v->sport;
      $l_start_date = $v->start_date;
      $l_end_date = $v->end_date;
      $l_link = $v->link_to_site;
      $l_phone = $v->train_phone;
      $l_fax = $v->train_fax;
      $l_contact = $v->train_contact;
      $l_email = $v->train_email;
    }
    break;
  default:
    $event_switch = 'league_id';
    $league = $wpdb->get_results("SELECT * FROM " . $event_type . " WHERE " . $event_switch . " = " . $league_id);

    foreach ($league as $v) {
      $l_name = $v->league_name;
      $l_region = $v->league_region;
      $l_sport = $v->sport;
      $l_start_date = $v->start_date;
      $l_end_date = $v->end_date;
      $l_link = $v->link_to_site;
      $l_phone = $v->league_phone;
      $l_fax = $v->league_fax;
      $l_contact = $v->league_contact;
      $l_email = $v->league_email;
    }
}

// get all regions
$regions = $wpdb->get_results("SELECT region_name FROM regions ORDER BY region_name");

// get all sports
$sports = $wpdb->get_results("SELECT sport_name FROM sports");

$logo_bool = "";

if (isset($_POST['submit_league'])) {
  if (empty($_POST['league_name'])) {
    echo '<p class="alert alert-danger" role="alert">League Name must be filled</p>'; 
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
          $logo_bool = 1;
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

    if ($logo_bool == 1) {
      $wpdb->update('leagues', array('link_to_site'=>$new_logo_path), array('league_id'=>$league_id));
    }

    $old_name = htmlspecialchars(strip_tags($_POST['league_name']));
    $name = str_ireplace("\\", "", $old_name);
    $region = htmlspecialchars(strip_tags($_POST['region']));
    $start_date = htmlspecialchars(strip_tags($_POST['start_date']));
    $end_date = htmlspecialchars(strip_tags($_POST['end_date']));
    $sport = htmlspecialchars(strip_tags($_POST['sport']));
    $link = htmlspecialchars(strip_tags($_POST['link']));
    $phone = htmlspecialchars(strip_tags($_POST['league_phone']));
    $fax = htmlspecialchars(strip_tags($_POST['league_fax']));
    $contact = htmlspecialchars(strip_tags($_POST['league_contact']));
    $email = htmlspecialchars(strip_tags($_POST['league_email']));

    $wpdb->update('leagues', array('league_name'=>$name, 'league_region'=>$region, 'sport'=>$sport, 'link_to_site'=>$link, 'league_phone'=>$phone, 'league_fax'=>$fax, 'league_contact'=>$contact, 'league_email'=>$email),array('league_id'=>$league_id));

    header("Location: my-leagues");
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
  section#edit_league_page h2 {
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
  article#form_article form#edit_league_form {
    padding: 30px;
    background-color: #a8a2a3;
  }
   @media screen and (max-width: 767px) {
    article#form_article form#edit_league_form {
      padding: 10px;
    }
  }
  article#form_article form#edit_league_form label {
    color: black;
  }
  span.required_mark {
    color: red;
  }
</style>

<section id="edit_league_page" class="container-fluid">
  <h2>Edit <?php echo $l_name; ?></h2>
  <article class="container-fluid" id="form_article">
    <div style="color:white;"><span class="required_mark">*</span> = required field</div>
    <form method="post" id="edit_league_form" enctype="multipart/form-data">
      <div class="form-group">
        <label for="league_name">League Name<span class="required_mark">*</span></label>
        <input type="text" class="form-control" id="league_name" name="league_name" value="<?php echo $l_name; ?>">
      </div>
      <div class="form-group">
        <label for="sport">Sport<span class="required_mark">*</span></label>
        <select name="sport" id="sport" class="form-control">
          <? foreach($sports as $sport): ?>
          <? if ($sport->sport_name == $l_sport): ?>
          <option value="<?php echo $sport->sport_name; ?>" selected><?php echo $sport->sport_name; ?></option>
          <? else: ?>
          <option value="<?php echo $sport->sport_name; ?>"><?php echo $sport->sport_name; ?></option>
          <? endif ?>
          <? endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label for="region">Region<span class="required_mark">*</span></label>
        <select name="region" id="region" class="form-control">
          <? foreach($regions as $region): ?>
          <? if ($region->region_name == $l_region): ?>
          <option value="<?php echo $region->region_name; ?>" selected><?php echo $region->region_name; ?></option>
          <? else: ?>
          <option value="<?php echo $region->region_name; ?>"><?php echo $region->region_name; ?></option>
          <? endif ?>
          <? endforeach ?>
        </select>
      </div>
      <? if ($event_type == 'camps' || $event_type == 'tournaments'): ?>
      <div class="form-group date_div">
        <label for="start_date">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $l->start_date; ?>">
      </div>
      <div class="form-group date_div">
        <label for="end_date">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $l->end_date; ?>">
      </div>
      <? endif ?>
      <div class="form-group">
        <label for="logo">League Logo</label>
        <input type="file" class="form-control" id="logo" name="logo">
      </div>
      <div class="form-group">
        <label for="link">Link to League Website</label>
        <input type="url" class="form-control" id="link" name="link" value="<?php echo $l_link; ?>">
      </div>
      <div class="form-group">
        <label for="league_phne">Phone</label>
        <input type="tel" class="form-control" id="league_phone" name="league_phone" value="<?php echo $l_phone; ?>">
      </div>
      <div class="form-group">
        <label for="league_fax">Fax</label>
        <input type="tel" class="form-control" id="league_fax" name="league_fax" value="<?php echo $l_fax; ?>">
      </div>
      <div class="form-group">
        <label for="league_email">Email</label>
        <input type="email" class="form-control" id="league_email" name="league_email" value="<?php echo $l_email; ?>">
      </div>
      <div class="form-group">
        <label for="league_contact">Contact</label>
        <input type="text" class="form-control" id="league_contact" name="league_contact" value="<?php echo $l_contact; ?>">
      </div>
      <button type="submit" class="btn btn-primary" id="submit_league" name="submit_league">Submit</button>
    </form>
  </article>
</section>
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {

    });
  })(jQuery);
</script>