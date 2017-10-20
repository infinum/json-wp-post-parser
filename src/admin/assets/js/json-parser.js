jQuery(document).ready(function($) {
  var $notificationElement = $('.js-processed-posts');
  var allPosts = $notificationElement.data('posts');

  function ajax_resave_post(postID, finished) {
    var data = {
      'parseNonce': wpApiSettings.nonce,
      'postID': postID,
    };

    $.ajax({
      type: 'POST',
      url: `${wpApiSettings.root}wp/v2/posts-parse-json/run`,
      data: data,
      beforeSend: function(xhr) {
        $notificationElement.html('');
        $notificationElement.append('<div>Processing...</div>');
        xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
      }
    })
    .done(function(data) {
      console.log(data);
      $notificationElement.append(`<div>${data}</div>`);
    })
    .fail(function(xhr, status, error) {
      $notificationElement.append(`<div>Error ${xhr.status}: ${error}</div>`);
    })
    .always(function(){
      if (finished) {
        $notificationElement.append('<div>Finished</div>');
      }
    });
  }

  $('.js-start-post-resave').on('click', function() {
    finished = false;
    for (ID in allPosts) {
      if (allPosts.hasOwnProperty(ID)) {
        if (ID === allPosts.length-1) {
          finished = true;
        }
        ajax_resave_post(allPosts[ID], finished);
      }
    }
  });

});
