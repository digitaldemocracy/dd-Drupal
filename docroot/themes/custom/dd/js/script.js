/* eslint-disable no-console */
/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth

(function (Drupal, $) {

  'use strict';

  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.toggle_nav_menu = {
    attach: function (context, settings) {
      var windowWidth = $(window).width();
      var menuWrapper = $('#block-dd-main-menu');
      var toggleNavMenu = $('.js-nav-menu-toggle');

      // @todo consider doing this with Javascript because using jQuery loads the library sitewide.
      $(toggleNavMenu).once('toggle-menu-block').each(function () {
        if (windowWidth < 999) {
          $(menuWrapper).hide();

          // Toggle function.
          $(toggleNavMenu).click(function () {
            $(toggleNavMenu).toggleClass('open');
            $(menuWrapper).toggle('slow');
            return false;
          });
        }
      });
    }
  };

  Drupal.behaviors.hearings = {
    attach: function (context, settings) {
      // Allow hearing play icon to function in showhide header.
      $('.hearing--play-icon a').click(function (event){
        event.preventDefault();
        window.location = $(this).attr('href');
      });
    }
  };

  Drupal.behaviors.showmore = {
    attach: function (context, settings) {
      // Variables
      var fullContent = $('.js-full-content');
      var excerpt = $('.js-excerpt');
      var showLink = $('.js-showmore-link');
      var hideLink = $('.js-hide-link');
      // Hide the full content field
      fullContent.hide();

      $(showLink).once('showMore').click(function () {
        excerpt.hide();
        fullContent.show();
      });
      $(hideLink).once('showLess').click(function () {
        excerpt.show();
        fullContent.hide();
      });
    }
  };

  // Redirect to other state site based on dropdown.
  Drupal.behaviors.stateselect = {
    attach: function (context, settings) {
      $('#state-select', context).change(function () {
        var state_url = $('option:selected', this).val().split('~');
        var url = state_url[1];
        window.location = url;
      });
    }
  };

  $(function () {
    // Set last state cookie based on selection dropdown.
    // @todo Make this work for homepage state selection.
    var state_select = $('#state-select option:selected').val();
    if (state_select) {
      var state_url = state_select.split('~');
      var state = state_url[0];

      // Set last state cookie for domain and local.
      $.cookie('dd_last_state', state, {domain: 'digitaldemocracy.org'});
      console.log('Set dd_last_state to ' + state);
    }
  });


  // We pass the parameters of this anonymous function are the global variables
  // that this script depend on. For example, if the above script requires
  // jQuery, you should change (Drupal) to (Drupal, jQuery) in the line below
  // and, in this file's first line of JS, change function (Drupal) to
  // (Drupal, $)
})(Drupal, jQuery);

