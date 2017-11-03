/* global wpApiSettings */
import $ from 'jquery';

const ajax = {
  ajaxResavePost(ID, finished, $notifElement) {
    if (typeof $notifElement === 'undefined') {
      return false;
    }

    const ajaxData = {
      postID: ID
    };

    $.ajax({
      type: 'POST',
      url: `${wpApiSettings.root}posts-parse-json/v1/run`,
      data: ajaxData,
      beforeSend: (xhr) => {
        $notifElement.html('');
        $notifElement.append(`<div>${wpApiSettings.processing}</div>`);
        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
      }
    })
      .done((data) => {
        $notifElement.append(`<div>${data}</div>`);
      })
      .fail((xhr, status, error) => {
        $notifElement.append(`<div>${wpApiSettings.error} ${xhr.status}: ${error}</div>`);
      })
      .always(() => {
        if (finished) {
          $notifElement.append(`<div>${wpApiSettings.finished}</div>`);
        }
      });
    return false;
  }
};

export default ajax;
