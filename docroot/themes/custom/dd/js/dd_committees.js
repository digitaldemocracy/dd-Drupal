(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.showhidegroup = {
    attach: function (context, settings) {
      var groupHeader = $('.dd-showhide-group h3');
      var groupBody = $('.dd-showhide-body');
      var groupWrapper = $('.dd-showhide-group');
      var toggle = $('<a class = "toggle">Show</a>');

      groupBody.hide();
      groupHeader.once('addToggle').append(toggle);

      groupWrapper.each(function (index) {
        var thisBody = $(this).find(groupBody);
        var thisHeader = $(this).find(groupHeader);
        var thisToggle = $(this).find('.toggle');

        $(thisHeader).once('toggleBody').click(function () {
          // Change toggle text onClick
          if ($.trim($(thisToggle).text()) === 'Show') {
            $(thisToggle).text('Hide');
          }
          else {
            $(thisToggle).text('Show');
          }
          // Toggle the body content
          thisBody.toggle('0', function () {
            // Add expanded class to the direct parent of the toggled body content
            $(thisHeader).toggleClass('expanded');
            // Add open class to the toggle link
            $(thisToggle).toggleClass('open');
          });
        });
      });
    }
  };
})(Drupal, jQuery);

