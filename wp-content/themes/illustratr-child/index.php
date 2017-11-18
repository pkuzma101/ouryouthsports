<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Illustratr
 */

$ads = $wpdb->get_results("SELECT * FROM advertisers");

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
  article#advertisement_article {
    width: 40%;
    margin: 0 auto;
    color: white;
    padding-bottom: 30px;
  }
  @media screen and (max-width: 767px) {
    article#advertisement_article  {
      width: 70%;
    }
  }
  p#ad_caption {
    text-align: center;
    font-size: 1.35em;
  }
  .carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
    display: block;
    width: 100%;
    height: 350px;
  }
  @media screen and (max-width: 767px) {
    .carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
      height: auto;
    }
  }
  article#clndr_article {
    width: 65%;
    margin: 0 auto;
    background: #a8a2a3;;
    padding: 25px;
    height: 585px; 
  }
  @media screen and (max-width: 767px) {
    article#clndr_article {
      width: auto;
    }
  }
  article#find_article {
    width: 100%;
    text-align: center;
    margin-top: 30px;
    margin-bottom: 30px;
  }
  article#find_article a {
    width: 25%;
    font-size: 1.9em;
    line-height: 80px;
  }
</style>

<section id="title_page" class="container-fluid">
  <article id="find_article">
    <a href="/ouryouthsports/find-event" class="btn btn-info">Find a Sports League</a>
  </article>
  <article id="advertisement_article">
    <p id="ad_caption">Our Sponsors</p>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      </ol>
      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="/ouryouthsports/wp-content/uploads/2017/05/carmel.gif" alt="...">
        </div>
        <? foreach ($ads as $ad): ?>
        <div class="item">
          <img src="<?php echo $ad->ad_path; ?>">
        </div>
        <? endforeach ?>
      </div>

      <!-- Controls -->
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </article><!-- advertisement_article -->
  <article id="clndr_article">
    <div id="clndr_div">
    <?php echo do_shortcode('[clndr id=full-size-calendar]'); // camp and tournament calendar ?>
    </div>
  </article>
</section><!-- title_page -->
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {

     
    });
  })(jQuery);
</script>
<?php get_footer(); ?>