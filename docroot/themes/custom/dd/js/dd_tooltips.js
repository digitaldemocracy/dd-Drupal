// Initializing jQuery tooltips.
(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.ddtooltips = {
    attach: function (context, settings) {
      $('.dd-share-toolbar-group-item').tooltip();
      $('.node-dd-email-subscription-form').tooltip();
      $('.node-dd-email-subscription-edit-form').tooltip();
    }
  };

})(Drupal, jQuery);
