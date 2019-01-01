// Javascript for the Persons Search views page
(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.ddtoggleaffiliations = {
    attach: function (context, settings) {
      var affiliationsToggle = $('.views-field-PersonAffiliations .speakers__search--affiliations-label');
      var affiliationsContent = $('.speakers__search--affiliations');

      $(affiliationsContent).hide();

      $(affiliationsToggle).click(function () {
        $(this).siblings(affiliationsContent).toggle(function () {
          $(this).siblings(affiliationsToggle).toggleClass('open');
        });
      });
    }
  };
})(Drupal, jQuery);
