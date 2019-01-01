// JS for the homepage
(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.ddscrollleft = {
    attach: function (context, settings) {
      var scrollContainer = $('.states-scroll');
      var previousArrow = $('.states-scroll--arrows .previous');
      var nextArrow = $('.states-scroll--arrows .next');
      // Initialize scroll classes
      nextArrow.addClass('scroll');

      previousArrow.click(function () {
        event.preventDefault();
        scrollContainer.animate({
          scrollLeft: '-=200px'
        });
      });

      nextArrow.click(function () {
        event.preventDefault();
        scrollContainer.animate({
          scrollLeft: '+=200px'
        });
      });
    }
  };

  // Add an advanced Search link with JS.
  Drupal.behaviors.ddAdvancedSearchLink = {
    attach: function (context, settings) {
      var searchLink = $('<a href= "/search" class = "js-advanced-search"> Advanced Search </a>');
      if (drupalSettings.site_type !== 'base') {
        $('#block-ddsitesearchblock').once('addSearchLink').append(searchLink);
      }
    }
  };

  // Homepage search autocomplete state selection.
  Drupal.behaviors.searchstate = {
    attach: function (context, settings) {
      // Trigger modal open link on base site homepage.
      if (drupalSettings.site_type === 'base') {
        jQuery('#edit-dd-search-term').click(function () {
          jQuery('#edit-stateselectlink').trigger('click');
        });
      }

      $('#edit-state', context).change(function () {
        var state = $('option:selected', this).val();
        var autocomplete_path = '/dd_search_autocomplete/' + state;
        $('#edit-dd-search-term').attr('data-autocomplete-path', autocomplete_path);

        // Clear autocomplete cache
        Drupal.autocomplete.cache = {};

        jQuery('#dd-search-form').attr('action', drupalSettings.site_domain_urls[state]);
        jQuery('.js-advanced-search').attr('href', drupalSettings.site_domain_urls[state] + '/search');
      });

    }
  };

  Drupal.behaviors.homepagevideo = {
    attach: function (context, settings) {
      $('#homepage-video', context).once('homepagevideo').each(function (index) {
        // Load video if viewport is < medium sizing.
        if (jQuery(window).width() >= 777) {
          $(this).append('<video autoplay="autoplay" loop="loop" src="/themes/custom/dd/banner.mp4"></video>');
        }
      });
    }
  };
})(Drupal, jQuery);
