<?php
/**
 * Template Name: Manage Region Page Template
 *
 * @package illustratr
 */

// get all sports
$areas = $wpdb->get_results("SELECT area_name FROM areas");

if (isset($_POST['add_btn'])) {
  if (empty($_POST['region_name']) || empty($_FILES['area_name'])) {
    echo '<p class="alert alert-danger" role="alert">Both fields must be filled</p>'; 
  } 
  else {
    $name = htmlspecialchars(strip_tags($_POST['region_name']));
    $area = htmlspecialchars(strip_tags($_POST['area_name']));

    $wpdb->insert('regions', array(
      'region_name'     => $name,
      'area_name'      => $area
    ));

    echo '<p class="alert alert-success">Region added!</p>';
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
  section#manage_region_page h2 {
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

<section id="manage_region_page" class="container-fluid">
  <article id="form_article" class="container-fluid">
    <h3>Create New Region</h3>
    <form method="post" id="add_region_form" enctype="multipart/form-data">
      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" id="region_name" name="region_name" required="true" />
      </div>
      <div class="form-group">
        <label>Area</label>
        <select class="form-control" id="area" name="area">
          <? foreach ($areas as $area): ?>
          <option value="<?php echo $area->area_name; ?>"><?php echo $area->area_name; ?></option>
          <? endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" id="add_btn" name="add_btn" class="btn btn-primary">Add Region</button>
      </div>
    </form>
    <a href="#" id="update_link" style="text-decoration:none;color:white;">Update Existing Region?</a>
  </article>
</section>