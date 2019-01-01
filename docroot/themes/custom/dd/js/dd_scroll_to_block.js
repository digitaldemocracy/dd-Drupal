/* eslint-disable no-console */


(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.scrollToBlock = {
    attach: function (context, settings) {
      // Add Show All buttons to Top Contributions / Gifts.
      $.fn.scrolltoblock = function (sourceBlock, targetHeader) {
        var showAll = $('<a class = "button js--scroll-to-block">Show all</a>');
        $(sourceBlock).append(showAll);
        $(showAll).click(function () {
          if (!targetHeader.hasClass('expanded')) {
            targetHeader.trigger('click');
          }
          $.scrollTo(targetHeader);
        });
      };

      // Move top contributions footer into view-content so it can be hidden with show-hide
      $('.view-display-id-top_person_contributions .view-footer').remove().appendTo('.view-display-id-top_person_contributions > .view-content');

      $('.view-display-id-top_person_contributions', context).once('scrolltoblock').each(function (index) {

        $(document).scrolltoblock($('.view-display-id-top_person_contributions .js--showall-placeholder'),
          $('.view-display-id-person_contributions > .view-header')
        );

        $(document).scrolltoblock($('.view-id-person_gifts_received_years.view-display-id-block_2 > .view-content'),
          $('.view-id-person_gifts_received_years.view-display-id-block_1 > .view-header')
        );
      });
    }
  };
  // We pass the parameters of this anonymous function are the global variables
  // that this script depend on. For example, if the above script requires
  // jQuery, you should change (Drupal) to (Drupal, jQuery) in the line below
  // and, in this file's first line of JS, change function (Drupal) to
  // (Drupal, $)
})(Drupal, jQuery);
