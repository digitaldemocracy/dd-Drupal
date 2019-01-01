(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.toggleList = {
    attach: function (context, settings) {
      // Toggle the list of Speaker know Affiliations in the Speaker top block
      $.fn.toggleList = function (listItem, itemCount) {
        var toggleLink = $('<a class = "js-toggle-people-list">Show All</a>');
        $(listItem).slice(itemCount).hide();
        // Add Show all link if there are more than 5 items
        if ($(listItem).length > 5) {
          $(listItem).parent().once('addLink').append(toggleLink);
        }

        // Toggle items
        $(toggleLink).click(function () {
          $(listItem).slice(itemCount).toggle();

          if ($(toggleLink).text() === 'Show All') {
            $(toggleLink).text('Hide').addClass('js-is-collapsed');
          }
          else {
            $(toggleLink).text('Show All').removeClass('js-is-collapsed');
          }
        });

      };

      $(document).toggleList('.js-person-affiliations ul li', '5');
      $(document).toggleList('.js-person-clients ul li', '5');
      $(document).toggleList('.js-person-employers ul li', '5');
    }
  };
})(Drupal, jQuery);
