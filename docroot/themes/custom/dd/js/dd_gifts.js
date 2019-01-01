/* eslint-disable strict */

(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.ddgifts = {
    attach: function (context, settings) {
      $('.views-exposed-form', context).once('yearselect').each(function (index) {
        $('#edit-year', this).on('change', function () {
          $('.views-exposed-form').submit();
        });
      });
    }
  };
})(Drupal, jQuery);
