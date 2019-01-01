/* eslint-disable strict */

(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.pastaffiliations = {
    attach: function (context, settings) {
      $('#block-views-block-person-top-block-former-block').hide();
      $('#block-views-block-person-top-block-current-block-1 .former-positions').hide();
    }
  };
})(Drupal, jQuery);
