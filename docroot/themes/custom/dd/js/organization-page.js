/* eslint-disable strict */
/* eslint-disable no-console */
// Javascript for individual Organization pages.
(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.ddtogglealignments = {
    attach: function (context, settings) {
      var listContainer = $('.organization__alignment--group');

      listContainer.each(function (index) {
        var listCount = $(this).find('li').length;
        var notFirstLi = $(this).find('li:not(:first-child)');
        var toggleLink = $('<a>Expand</a>');

        if (listCount > 1) {
          // Hide extra list items. Only display the first item.
          notFirstLi.hide();
          // Add a link to toggle the extra list items
          $(this).once('addcollapse').prepend(toggleLink);
          toggleLink.addClass('organization__alignment--toggle');

          // Use link to toggle extra li items
          $(toggleLink).click(function () {
            // Change toggle text onClick.
            if ($.trim($(toggleLink).text()) === 'Expand') {
              $(toggleLink).text('Collapse');
            }
            else {
              $(toggleLink).text('Expand');
            }

            // Show & hide extra li items and toggle an open class
            $(notFirstLi).toggle(function () {
              $(toggleLink).toggleClass('open');
            });
          });
        }


      });
    }
  };

})(Drupal, jQuery);
