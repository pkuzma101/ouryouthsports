<?php
/**
 * Template Name: Find Event Page Template
 *
 * @package illustratr
 */


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
  section#title_page article#sports_sel_box {
    width: 85%;
    margin: 0 auto;
    background: #404040;
  }
  @media screen and (max-width: 767px) {
    article#sports_sel_box{
      width: 100%;
      padding-bottom: 25px;
    }
  }
  section#title_page h2 {
    color: white;
  }
  div.row {
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 30px;
  }
  @media screen and (max-width: 767px) {
    div.row {
     padding: 0px;
     padding-top:20px;
    }
  }
  @media screen and (max-width: 767px) {
    div#go_back_div {
     padding-bottom: 15px;
    }
  }
  div.col-md-4 a {
    text-decoration: none;
  }
  div.thumbnail {
    margin-bottom: 30px;
  }
  div.thumbnail img {
    height: 350px;
    width: 100%;
  }
  @media screen and (max-width: 767px) {
    div.thumbnail img {
      height: 150px;
      width: 100%;
    }
  }
  div.thumbnail div.caption {
    font-size: 1.8em;
    text-align: center;
  }

  section {
    padding-bottom: 25px;
  }
  div.col-md-6 {
    padding: 30px;
  }
  @media screen and (max-width: 767px) {
    div.col-md-6 {
      padding: 0px;
      padding-bottom: 15px;
    }
  }
  div.league_card {
    height: 400px;
    width: 100%;
    border: 1px solid black;
    background: white;
    /*overflow: scroll;*/
    margin: 0 auto;
    padding-left: 15px;
  }
  @media screen and (max-width: 767px) {
    div.league_card {
      height: auto;
      padding: 0px;
      padding-bottom: 15px;
      padding-left: 6px;
      padding-top: 5px;
    }
  }
  @media screen and (max-width: 767px) {
    div#league_row {
      padding: 0px;
    }
  }
  div.league_card h4 {
    font-size: 1.4em;
  }
  @media screen and (max-width: 767px) {
    div.league_card h4 {
      font-size: 1em;
    }
  }
  div.league_card div.logo_div {
    height: 150px;
  }
  @media screen and (max-width: 767px) {
    div.league_card div.logo_div {
      display: none;
    }
  }
  div.league_card p, div.league_card span {
    color: black;
    font-weight: bold;
    font-size: 1em;
  }
  a.event_link {
    text-decoration: none;
  }
  a div.exp {
    height: 150px;
    color: white;
    font-size: 1.2em;
    text-align: center;
    padding: 8px;
    background: #f23838;
    text-decoration: none;
  }
</style>

<!-- Modal for Emailing -->
<div id="email_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Email this Contact</h4>
      </div>
      <div class="modal-body">
        <form id="email_form" method="post" role="form">
          <div class="form-group row">
            <label for="email_to">To: </label>
            <input type="text" class="form-control" id="email_to" name="email_to">
          </div>
          <div class="form-group row">
            <label for="subject">Subject: </label>
            <input type="text" class="form-control" id="email_subject" name="email_subject">
          </div>
          <div class="form-group row">
            <label for="subject">Message: </label>
            <textarea class="form-control" id="email_message" name="email_message" rows="8" cols="50"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info" id="send_email_btn" name="send_email_btn" style="float: left;">Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- email_modal -->

<section id="title_page" class="container-fluid">
  <h2 id="select_header" style="text-align: center;">Select a Sport</h2>
  <input type="hidden" id="sport" name="sport">
  <input type="hidden" id="area" name="area">
  <input type="hidden" id="event_type" name="event_type">
  <article class="container-fluid" id="sports_sel_box"></article>
</section>
<script type="text/javascript">
  (function($) {
    $(document).ready(function() {
      // function that creates sport select cards
      function select_sport() {
        $('#go_back_div').remove();
        $("#select_header").html("Select a Sport");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_sports'
        }
        $.post(ajaxurl, data, function(response) {
          $('#sports_sel_box').empty();
          $('#sports_sel_box').append(response);

          // fill sport input field appropriately
          $('a.sport_link').click(function() {
            var self = $(this);
            var sport = self.attr('id');
            $('input#sport').val(sport);
            var sport_val = $('input#sport').val();
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              if (sport == 'Football') {
                select_football_type();
              }
              else {
                select_event();
              }
            });
          });
        });
      }

      // function that gives choice between types of football
      function select_football_type() {
        $('#select_header').html("Which Type of Football?");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_football_types'
        }
        $.post(ajaxurl, data, function(response) {
          $('#sports_sel_box').empty();
          $('#sports_sel_box').hide().append(response).fadeIn(500);

          // remake sport cards if go back button is clicked
          $('a#go_back_btn').click(function() {
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              select_sport();
            });
          });

          $('a.type_link').click(function() {
            var self = $(this);
            var f_type = self.attr("id");
            $('input#sport').val(f_type);
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              select_event();
            });
          });
        });
      }

      // function that creates type of event
      function select_event() {
        $("#select_header").html("League, Camp, Tournament, or Training?");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_events'
        }
        $.post(ajaxurl, data, function(response) {
          $('#sports_sel_box').empty();
          $('#sports_sel_box').hide().append(response).fadeIn(500);

          // remake sport cards if go back button is clicked
          $('a#go_back_btn').click(function() {
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              select_sport();
            });
          });

          $('a.event_link').click(function() {
            var self = $(this);
            var event_type = self.attr("id");
            $('input#event_type').val(event_type);
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              select_area();
            });
          });
        });
      }

      // function that replace sport cards with area select
      function select_area() {
        $("#select_header").html("Which Part of Indianapolis?");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_regions'
        }
        $.post(ajaxurl, data, function(response) {
          $('#sports_sel_box').empty();
          $('#sports_sel_box').hide().append(response).fadeIn(500);

          // remake sport cards if go back button is clicked
          $('a#go_back_btn').click(function() {
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              select_sport();
            });
          });

          $('a.area_link').click(function() {
            var self = $(this);
            var area = self.attr("id");
            $('input#area').val(area);
            $('div.row').fadeOut('fast', function() {
              $(this).remove();
              get_leagues();
            });
          });
        });
      }

      function get_leagues() {
        var sport = $('input#sport').val();
        var area = $('input#area').val();
        var event_type = $('input#event_type').val();
        $('#select_header').html(sport + " " + event_type + "s on the " + area + " side");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_leagues',
          selected_sport: sport,
          selected_area: area,
          selected_event: event_type
        }
        $.post(ajaxurl, data, function(response) {
          $('#sports_sel_box').css("width", "100%");
          $('#sports_sel_box').empty();
          $('#sports_sel_box').hide().append(response).fadeIn(500);
        });
      }
      
      // make sport cards appear
      select_sport();

    });
  })(jQuery);
</script>
<?php get_footer(); ?>