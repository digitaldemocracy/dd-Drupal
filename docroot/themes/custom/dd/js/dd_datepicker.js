/* eslint-disable strict */

(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.dddatepicker = {
    attach: function (context, settings) {

      $('#edit-start-date').datepicker();
      $('#edit-end-date').datepicker();
      $('#edit-date-ts').datepicker({
        dateFormat: 'yy-mm-dd'
      });
      $('#edit-date-ts-1').datepicker({
        dateFormat: 'yy-mm-dd'
      });

    }
  };
})(Drupal, jQuery);
