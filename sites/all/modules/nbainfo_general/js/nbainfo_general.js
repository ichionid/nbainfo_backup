(function ($) {
  Drupal.behaviors.nbainfo_general = {
    attach: function (context, settings) { 
      $('#logo').css({ "max-height": $('#header img').height() + 'px' });
      var page = window.location.pathname;
      if (page=='/nbalive') {
        $("#logo img").fadeOut(2000);
        $("#logo").attr("src", "/sites/default/files/nbainfologo2.png");
        $("#logo img").attr("src", "/sites/default/files/nbainfologo2.png");
        $("#logo img").fadeIn(500);
      }
      $('div.pane-shoutbox-shoutbox h2').replaceWith('<h2 class="pane-title"><a href="shoutbox">Chat</a></h2>');
      var getInitialTopicChoice = $( "#edit-taxonomy-forums-und" ).find(":selected").text();
      nbaIfoTopicArrangeDisplay(getInitialTopicChoice);
      $( "#edit-taxonomy-forums-und" ).change(function() {
        var topic = $(this).find(":selected").text();
        nbaIfoTopicArrangeDisplay(topic);
      });
      // Find all table cells and append <p>
      $('#custom-table tr td').each(function() {
  	$(this).html('<p>' + $(this).html() + '</p>');
      });
      // Fix width of forum post.
      var minPostWidth;
      $('.node-type-forum .forum-left-side .forum-post').each(function() {
  	minPostWidth = $(this).width();
        return false;//exit
      });

      $('.node-type-forum .forum-post').each(function() {
  	if (minPostWidth < $(this).width()) {
  	  $(this).width(minPostWidth);
        }
      });
      $('.node-type-forum .forum-post-footer').each(function() {
        if (minPostWidth < $(this).width()) {
          $(this).width(minPostWidth);
        }
      });
      $('.node-type-forum .title.comment-form').width(minPostWidth);
      $('.node-type-forum .comment-form').width(minPostWidth);
    }
  };
  function  nbaIfoTopicArrangeDisplay(topic) {
    if (topic == "General discussion") {
      $("#edit-field-team").hide();
      $("#edit-field-player").hide();
      $("#edit-field-embed-player-s-stats-link").hide();
      $("#edit-field-topic-description").show();
    } else if(topic == "-Spillere") {
      $("#edit-field-team").hide();
      $("#edit-field-player").show();
      $("#edit-field-embed-player-s-stats-link").show();
      $("#edit-field-topic-description").hide();
    } else if(topic == "-Hold") {
      $("#edit-field-team").show();
      $("#edit-field-player").hide();
      $("#edit-field-embed-player-s-stats-link").hide();
      $("#edit-field-topic-description").hide();
    } else {
      $("#edit-field-team").hide();
      $("#edit-field-player").hide();
      $("#edit-field-embed-player-s-stats-link").hide();
      $("#edit-field-topic-description").show();
    }
  }
})(jQuery);

