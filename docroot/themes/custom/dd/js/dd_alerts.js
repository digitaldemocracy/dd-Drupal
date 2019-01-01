// JS for the homepage
(function (Drupal, $) {

  'use strict';
// Homepage search autocomplete state selection.
  Drupal.behaviors.ddalerts = {
    attach: function (context, settings) {
      $('#edit-alert-state', context).change(function () {
        var state_select = $('option:selected', this).val();
        if (state_select) {
          var state_url = state_select.split('~');
          window.location = state_url[1];
        }
      });
    }
  };
})(Drupal, jQuery);
