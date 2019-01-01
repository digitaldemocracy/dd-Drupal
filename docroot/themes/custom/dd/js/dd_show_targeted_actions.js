// JS for the homepage
(function (Drupal, $) {

  'use strict';
// Homepage search autocomplete state selection.
  Drupal.behaviors.ddshowtargetedactions = {
    attach: function (context, settings) {
      $('.actions--recipients').show();
    }
  };
})(Drupal, jQuery);
