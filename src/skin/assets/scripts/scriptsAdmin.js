import $ from 'jquery';
import ajaxHelper from './ajax-helper';

$(function() {

  const $notificationElement = $('.js-processed-posts');
  const allPosts = $notificationElement.data('posts');

  $('.js-start-post-resave').on('click', function() {
    let finished = false;
    for (const ID in allPosts) {
      if (Object.prototype.hasOwnProperty.call(allPosts, ID)) {
        if (parseInt(ID, 10) === parseInt(allPosts.length - 1, 10)) {
          finished = true;
        }
        ajaxHelper.ajaxResavePost(allPosts[ID], finished, $notificationElement);
      }
    }
  });
});
