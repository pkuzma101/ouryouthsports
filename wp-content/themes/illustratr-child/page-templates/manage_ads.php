<?php
/**
 * Template Name: Manage Ads Page Template
 *
 * @package illustratr
 */

require(ABSPATH . "wp-admin" . '/includes/image.php');
require(ABSPATH . "wp-admin" . '/includes/file.php');
require(ABSPATH . "wp-admin" . '/includes/media.php');

// get all sports
$ads = $wpdb->get_results("SELECT * FROM advertisemers");

if (isset($_POST['add_btn'])) {
  if (empty($_POST['company_name']) || empty($_FILES['ad_pic'])) {
    echo '<p class="alert alert-danger" role="alert">Both fields must be filled</p>'; 
  } 
  else {
    $pic_type = $_FILES['ad_pic']['type'];
    if ($pic_type == 'image/jpg' || $pic_type == 'image/png' || $pic_type == 'image/gif' || $pic_type == 'image/jpeg') {
      foreach ($_FILES as $file => $array) {
        if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
            return "upload error : " . $_FILES[$file]['error'];
        }
        $attach_id = media_handle_upload($file, 0);
      }
    }
    else {
      echo '<p class="alert alert-danger" role="alert">Sport pic must be jpg, jpeg, png, or gif</p>'; 
      die;
    }

    // create entry for league_logo
    $pic_name = basename($_FILES['ad_pic']['name']);
    $pic_type = $_FILES['ad_pic']['type'];
    // $logo_path = wp_upload_dir();
    // $logo_path = $logo_path['baseurl'];
    $pic_path = '/ouryouthsports/wp-content/uploads';
    $year = date("Y");
    $month = date("m");
    $new_pic_path = $pic_path . '/' . $year . '/' . $month . '/' . $pic_name;

    $name = htmlspecialchars(strip_tags($_POST['company_name']));

    $wpdb->insert('advertisers', array(
      'company_name'     => $name,
      'ad_path'           => $new_pic_path
    ));

    echo '<p class="alert alert-success">Advertisement added!</p>';
  }
}

// if (isset($_POST['edit_btn'])) {
//   if (empty($_POST['sport_name']) || empty($_FILES['sport_pic'])) {
//     echo '<p class="alert alert-danger" role="alert">Both fields must be filled</p>'; 
//   }
//   else {
//     if ($_FILES['sport_pic']['type'] != '') {
//       $pic_type = $_FILES['sport_pic']['type'];
//       if ($pic_type == 'image/jpg' || $pic_type == 'image/png' || $pic_type == 'image/gif' || $pic_type == 'image/jpeg') {
//         foreach ($_FILES as $file => $array) {
//           if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
//               return "upload error : " . $_FILES[$file]['error'];
//           }
//           $attach_id = media_handle_upload($file, 0);
//           $pic_bool = 1;
//         }
//       }
//       else {
//         echo '<p class="alert alert-danger" role="alert">Sport pic must be jpg, jpeg, png, or gif</p>'; 
//         die;
//       }

//       // update sport pic
//       $sport_id = $_POST['sport_id'];
//       $pic_name = basename($_FILES['pic']['name']);
//       $pic_type = $_FILES['pic']['type'];
//       // $pic_path = wp_upload_dir();
//       // $pic_path = $pic_path['baseurl'];
//       $pic_path = '/ouryouthsports/wp-content/uploads';

//       $year = date("Y");
//       $month = date("m");

//       $new_pic_path = $pic_path . '/' . $year . '/' . $month . '/' . $pic_name;

//       if ($pic_bool == 1) {
//         $wpdb->update('sports', array('sport_pic'=>$new_pic_path), array('sport_id'=>$sport_id));
//       }
//     }
    
//     // update name of sport
//     $sport_name = htmlspecialchars(strip_tags($_POST['sport_name']));
//     $sport_id = $_POST['sport_id'];

//     $wpdb->update('sports', array('sport_name'=>$sport_name), array('sport_id'=>$sport_id));  
    
//     header("Location: /ouryouthsports.com");  
//   }
// }

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
  section#manage_ads_page h2 {
    text-align: center;
    color: white;
  }
  article#form_article h3 {
    color: white;
    text-align: center;
  }
  article#form_article {
    width: 50%;
  }
  article#form_article form {
    padding: 30px;
    background-color: #a8a2a3;
  }
  article#form_article form label {
    color: black;
  }
</style>

<?php 
if (current_user_can('administrator')) { ?>

<section id="manage_ads_page" class="container-fluid">
  <article id="form_article" class="container-fluid">
    <h3>Create New Sport</h3>
    <form method="post" id="add_ad_form" enctype="multipart/form-data">
      <div class="form-group">
        <label>Company Name</label>
        <input type="text" class="form-control" id="company_name" name="company_name" required="true" />
      </div>
      <div class="form-group">
        <label>Ad Photo</label>
        <input type="file" class="form-control" id="ad_pic" name="ad_pic" />
      </div>
      <div class="form-group">
        <button type="submit" id="add_btn" name="add_btn" class="btn btn-primary">Add Ad</button>
      </div>
    </form>
    <a href="#" id="update_link" style="text-decoration:none;color:white;">Update Existing Ad?</a>
  </article>
</section>

<?php } 
      else {
        header('Location: ouryouthsports/');
      }
  ?>
<?php get_footer(); ?>