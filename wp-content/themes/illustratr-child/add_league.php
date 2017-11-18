<?php
/**
 * Template Name: Add User Template
 *
 * @package illustratr
 */

if (isset($_POST['submit_user'])) {
  if (!empty($_POST['user_name']) || !empty($_POST['user_email']) || !empty($_POST['user_password'])) {

    // hash the password
    $hashed_password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
    
    $name = htmlspecialchars(strip_tags($_POST['user_name']));
    $email = htmlspecialchars(strip_tags($_POST['user_email']));

    $email_check = $wpdb->get_results("SELECT user_email FROM wp_users WHERE user_email = " . $email);
    if (!empty($email_check)) {
      echo '<p class="alert alert-danger" role="alert">That email address has already been taken</p>'; 
    }
    else {
      $wpdb->insert('wp_users', array(
        'user_login'    => $email,
        'user_pass'     => $hashed_password,
        'user_email'    => $email,
        'display_name'  => $name
      ));
    }
    echo '<p class="alert alert-success" role="alert">New user created!</p>';
  }
  else {
    echo '<p class="alert alert-danger" role="alert">All fields must be filled</p>';
  }
}

get_header(); ?>

<section id="new_user_page" class="container-fluid">
  <h2>Create a New User</h2>
  <article id="form_article" class="container-fluid">
    <form method="post" id="new_user_form">
      <div class="form-group">
        <label for="name">First and Last Name</label>
        <input type="text" id="user_name" name="user_name" placeholder="John Doe" />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="user_email" name="user_email" placeholder="example@example.com" />
      </div>
      <div class="form-group">
        <label for="name">Password</label>
        <input type="password" id="user_password" name="user_password" />
      </div>
      <button type="submit" class="btn btn-primary" id="submit_user" name="submit_user">Submit</button>
    </form>
  </article>
</section>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>