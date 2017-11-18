<?php
/**
 * Template Name: Manage Sport Page Template
 *
 * @package illustratr
 */

require(ABSPATH . "wp-admin" . '/includes/image.php');
require(ABSPATH . "wp-admin" . '/includes/file.php');
require(ABSPATH . "wp-admin" . '/includes/media.php');

// get all sports
$sports = $wpdb->get_results("SELECT sport_id, sport_name FROM sports");

$pic_bool=0;

if (isset($_POST['add_btn'])) {
  if (empty($_POST['sport_name']) || empty($_FILES['sport_pic'])) {
    echo '<p class="alert alert-danger" role="alert">Both fields must be filled</p>'; 
  } 
  else {
    $pic_type = $_FILES['sport_pic']['type'];
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
    $pic_name = basename($_FILES['sport_pic']['name']);
    // $pic_type = $_FILES['sport_pic']['type'];
    // $logo_path = wp_upload_dir();
    // $logo_path = $logo_path['baseurl'];
    $pic_path = '/ouryouthsports/wp-content/uploads';
    $year = date("2017");
    $month = "05";
    $new_pic_path = $pic_path . '/' . $year . '/' . $month . '/' . $pic_name;

    $name = htmlspecialchars(strip_tags($_POST['sport_name']));

    $wpdb->insert('sports', array(
      'sport_name'     => $name,
      'sport_pic'      => $new_pic_path
    ));

    echo '<p class="alert alert-success">Sport added!</p>';
  }
}

if (isset($_POST['edit_btn'])) {
  if (empty($_POST['sport_name']) || empty($_FILES['sport_pic'])) {
    echo '<p class="alert alert-danger" role="alert">Both fields must be filled</p>'; 
  }
  else {
    if ($_FILES['sport_pic']['type'] != '') {
      $pic_type = $_FILES['sport_pic']['type'];
      if ($pic_type == 'image/jpg' || $pic_type == 'image/png' || $pic_type == 'image/gif' || $pic_type == 'image/jpeg') {
        foreach ($_FILES as $file => $array) {
          if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
              return "upload error : " . $_FILES[$file]['error'];
          }
          $attach_id = media_handle_upload($file, 0);
          $pic_bool = 1;
        }
      }
      else {
        echo '<p class="alert alert-danger" role="alert">Sport pic must be jpg, jpeg, png, or gif</p>'; 
        die;
      }

      // update sport pic
      $sport_id = $_POST['sport_id'];
      $pic_name = basename($_FILES['sport_pic']['name']);
      // $pic_type = $_FILES['sport_pic']['type'];
      // $pic_path = wp_upload_dir();
      // $pic_path = $pic_path['baseurl'];
      $pic_path = '/ouryouthsports/wp-content/uploads';

      $year = date("2017");
      // $month = date("m");
      $month = "05";

      $new_pic_path = $pic_path . '/' . $year . '/' . $month . '/' . $pic_name;
      // var_dump($pic_name);
      // var_dump($new_pic_path);
      // die;

      if ($pic_bool == 1) {
        $wpdb->update('sports', array('sport_pic'=>$new_pic_path), array('sport_id'=>$sport_id));
      }
    }
    
    // update name of sport
    $sport_name = htmlspecialchars(strip_tags($_POST['sport_name']));
    $sport_id = $_POST['sport_id'];

    $wpdb->update('sports', array('sport_name'=>$sport_name), array('sport_id'=>$sport_id));  
    
    header("Location: /ouryouthsports/");  
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
  section#manage_sport_page h2 {
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

<section id="manage_sport_page" class="container-fluid">
  <article id="form_article" class="container-fluid">
    <h3>Create New Sport</h3>
    <form method="post" id="add_league_form" enctype="multipart/form-data">
      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" id="sport_name" name="sport_name" required="true" />
      </div>
      <div class="form-group">
        <label>Background Photo</label>
        <input type="file" class="form-control" id="sport_pic" name="sport_pic" />
      </div>
      <div class="form-group">
        <button type="submit" id="add_btn" name="add_btn" class="btn btn-primary">Add Sport</button>
      </div>
    </form>
    <a href="#" id="update_link" style="text-decoration:none;color:white;">Update Existing Sport?</a>
  </article>
</section>
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {
      $('a#update_link').click(function() {
        $('#form_article').empty();
        $('#form_article').append('<h3>Choose Sport to Edit</h3>');
        $('#form_article').append('<div style="text-align:center;"><select id="sport_to_edit" name="sport_to_edit"></select></div>');
        $('#sport_to_edit').append('<option value="nil">Select Sport...</option>');
        <? foreach($sports as $sport): ?>
        $('#sport_to_edit').append('<option value="<?php echo $sport->sport_id; ?>"><?php echo $sport->sport_name; ?></option>');
        <? endforeach ?>

        $('#sport_to_edit').change(function() {
          var sport_id = $(this).val();
          var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
          var data = {
            action: 'update_sport',
            data: sport_id
          }
          $.post(ajaxurl, data, function(response) {
            $('#form_article').empty();
            $('#form_article').append(response);
          });

        });

      });
    });
  })(jQuery);
</script>
<?php get_footer(); ?>