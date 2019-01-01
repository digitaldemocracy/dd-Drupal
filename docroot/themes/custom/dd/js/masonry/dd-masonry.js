/* eslint-disable strict */

(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.ddmasonry = {
    attach: function (context, settings) {
      $('.is-path-action-center .view-content').masonry({
        itemSelector: '.campaign-teaser--item',
        // columnWidth: 150,
        gutter: 5
      });
    }
  };
})(Drupal, jQuery);
