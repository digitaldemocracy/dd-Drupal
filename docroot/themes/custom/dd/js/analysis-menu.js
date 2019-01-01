/* eslint-disable no-console */
/*
* This function is to add the active-trail class to menu items.
* Used when you need a menu link to have an active class when you land on a page that is not part of the menu structure.
* To use it, you need to first add a class to the menu item that you want to have the active class using the menu attributes module.
* Call the function with that menu class and add the class for an element within the page/view.
*/


(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.addActiveTrailClass = {
    attach: function (context, settings) {
      // Create function for reuse
      $.fn.addActiveTrailClass = function (menuClass, viewClass) {
        if ($(viewClass).length) {
          $(menuClass).parent().addClass('menu-item--active-trail');
        }
      };

      // Call function in Gift pages
      $(this).addActiveTrailClass('.menu-item-gifts', '.view-gifts');
      // Call in Alignment pages
      $(this).addActiveTrailClass('.menu-item-alignments', '.view-alignments');
    }
  };

})(Drupal, jQuery);
