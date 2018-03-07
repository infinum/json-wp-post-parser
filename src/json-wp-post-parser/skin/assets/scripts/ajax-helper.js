/* global wpApiSettings */
import $ from 'jquery';

const ajax = {
  ajaxResavePost(ID, finished, $notifElement) {
    if (typeof $notifElement === 'undefined') {;
      return false;
    }

    const ajaxData = {
      postID: ID,
    };

    const $finishedElement = $('.js-finished');
    const $processingElement = $('.js-processing');

    $.ajax({
      type: 'POST',
      url: `${wpApiSettings.root}posts-parse-json/v1/run`,
      data: ajaxData,
      beforeSend: (xhr) => {
        $notifElement.html('');
        $finishedElement.html('');
        $processingElement.html(wpApiSettings.processing);
        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
      },
    })
      .done((data) => {
        $notifElement.append(`<div class="processed-posts__process-data">${data}</div>`);
      })
      .fail((xhr, status, error) => {
        $notifElement.append(`<div class="processed-posts__process-error">${wpApiSettings.error} ${xhr.status} (${ID}): ${error}</div>`);
      })
      .always(() => {
        if (finished) {
          $processingElement.html('');
          $finishedElement.html(wpApiSettings.finished);
        }
      });
    return false;
  },
};

export default ajax;
