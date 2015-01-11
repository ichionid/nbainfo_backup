(function ($) {
  Drupal.behaviors.nbainfo_forum = {
    attach: function (context, settings) {
      $('a.promote-comment-to-frontpage').click(function( event ) {
        event.preventDefault();
        promoteCommentToFrontpage($(this).attr('comment_id'));
        $(this).fadeIn(100).fadeOut(500).fadeIn(400);
      });
    }
  };
  function promoteCommentToFrontpage(commentId) {
  $.ajax({
        type: "POST",
        url: "http://nbainfo.sniffingmusicians.com/comment/" + commentId + "/promote_to_front",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        /*success: function (msg, textStatus, XMLHTTPRequest) {
           //Some Code
        },
        error: function (XMLHTTPRequest, textStatus, errorThrown) {
            //Some handler
        },*/
        cache: false
    });   
  }
})(jQuery);

