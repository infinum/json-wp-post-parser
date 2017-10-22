/* global wpApiSettings */
import $ from 'jquery';

const ajax = {
  ajaxResavePost(ID, finished, $notifElement) {
    if (typeof $notifElement === 'undefined') {
      return false;
    }

    const ajaxData = {
      parseNonce: wpApiSettings.nonce,
      postID: ID
    };

    $.ajax({
      type: 'POST',
      url: `${wpApiSettings.root}wp/v2/posts-parse-json/run`,
      data: ajaxData,
      beforeSend: (xhr) => {
        $notifElement.html('');
        $notifElement.append('<div>Processing...</div>');
        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
      }
    })
      .done((data) => {
        $notifElement.append(`<div>${data}</div>`);
      })
      .fail((xhr, status, error) => {
        $notifElement.append(`<div>Error ${xhr.status}: ${error}</div>`);
      })
      .always(() => {
        if (finished) {
          $notifElement.append('<div>Finished</div>');
        }
      });
    return false;
  }
};

export default ajax;

