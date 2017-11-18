<?php
/**
 * Template Name: My Leagues Template
 *
 * @package illustratr
 */

$user_id = get_current_user_id();

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
  section h2 {
    text-align: center;
    color: white;
  }
  div.col-md-4 {
    padding-bottom: 25px;
  }
  div#switch_box {
    text-align: center;
    padding-bottom: 25px;
  }
  div#switch_box a {
    font-size: 1.5em;
    padding: 10px;
    border: 3px solid white;
    text-decoration: none;
    color: white;
    -moz-transition: all 300ms;
    -o-transition: all 300ms;
    -webkit-transition: all 300ms;
    transition: all 300ms; 
  }
  div#switch_box a:hover {
    border: 0px;
    background-color: white;
    text-decoration: none;
    color: black;
    font-weight: bold;
    -moz-transition: all 300ms;
    -o-transition: all 300ms;
    -webkit-transition: all 300ms;
    transition: all 300ms; 
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
  div.col-md-6 {
    padding: 30px;
  }
  @media screen and (max-width: 767px) {
    div.col-md-6 {
      padding: 0px;
      padding-bottom: 15px;
    }
  }
  div.button_div button {
    width: 130px;
  }
  table#type_table {
    border: none;
  }
  table#type_table td {
    border: none;
    text-align: center;
  }
  table#type_table td a {
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

<section id="my_leagues_page" class="container-fluid">
  <h2>My Leagues</h2>
  <article id="league_box" class="container-fluid">
    <?php
    if (current_user_can('administrator')): ?>
    <div id="switch_box">
      <a href="#" id="switch_btn" data-id="all">View All Leagues</a>
    </div>
    <?php endif ?>
    <table id="type_table">
      <tr>
        <td><a href="#" id="leagues" class="type_opt btn btn-default">My Leagues</a></td>
        <td><a href="#" id="camps" class="type_opt btn btn-default">My Camps</a></td>
        <td><a href="#" id="tournaments" class="type_opt btn btn-default">My Tournaments</a></td>
        <td><a href="#" id="trainings" class="type_opt btn btn-default">My Trainings</a></td>
      </tr> 
    </table>
    <input type="hidden" id="event_type" name="event_type" value="leagues">
  </article>
</section>

<script type="text/javascript">
  (function($) {
    $(document).ready(function() {

      function get_events() {
        var event_type = $('input#event_type').val();
        $('h2.none').remove();
        var user_id = "<?php echo $user_id; ?>";
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        var data = {
          action: 'get_my_events',
          event_type: event_type,
          user_id: user_id
        }
        $.post(ajaxurl, data, function(response) {
          $('#league_box').append(response);
          var new_box = $('div.row');
          var num_children = 0;
          new_box.children().each(function() {
            num_children += 1;
          });
          if (num_children == 0) {
            $('#league_box').append('<h2 class="none">You currently have no ' + event_type);
          }

          // deleting a league
          $('.delete_btn').click(function() {
            var result = confirm("Delete this program?  You won't be able to bring it back.");
            if (result) {
              var self = $(this);
              var league_id = self.parent().parent().parent().attr("id");
              var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
              var data = {
                action: 'delete_league',
                data: league_id,
                event_type: event_type
              }
              $.post(ajaxurl, data, function(response) {
                var league_card = self.parent().parent().parent();
                league_card.remove();
              });
            }
          });
        });
      }

      // get all leagues if admin, or switch back
      $('#switch_btn').click(function() {
        var leagues = $(this).attr("data-id");
        var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
        $('div.row').fadeOut('fast', function() {
          $(this).remove();
        });
        var data = {
          action: 'get_all_leagues',
          selection: leagues
        }
        $.post(ajaxurl, data, function(response) {
          $('#league_box').append(response);
          $('#switch_btn').attr("data-id", "mine");
        });
      });

      // when you switch between event types, update link changes accordingly
      $('a.type_opt').click(function() {
        var event_type = $(this).attr("id");
        $('input#event_type').val(event_type);
        $('div.row').fadeOut('fast', function() {
          $(this).remove();
        });
        get_events();
      });

      get_events();
    });
  })(jQuery);
</script>
<?php get_footer(); ?>