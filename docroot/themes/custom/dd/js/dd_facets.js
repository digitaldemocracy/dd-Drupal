/* eslint-disable strict */

(function (Drupal, $) {

  'use strict';

  Drupal.behaviors.ddfacets = {
    attach: function (context, settings) {
      var siteState = null;
      var state_select = $('#state-select option:selected').val();
      if (state_select) {
        var state_url = state_select.split('~');
        siteState = state_url[0];
      }
      if (siteState !== 'FL') {
        // Loop through Session year facet and add -year+1 to it.
        $('[data-drupal-facet-id=session_year],[data-drupal-facet-id=hearing_session_year]').find('.facet-item__value').each(function () {
          var year = parseInt($(this).text());
          $(this).text(year + '-' + (year + 1));
        });
      }

      // Check all session year facets if date range is entered.
      $('#block-exposedformhearings-facetedpage-1 #edit-start-date, #block-exposedformhearings-facetedpage-1 #edit-end-date').on('change', function () {
        var selectedDate = new Date($(this).val());
        var selectedYear = selectedDate.getFullYear();
        var selectedSessionYear = selectedYear % 2 ? selectedYear : selectedYear - 1;
        var foundYear = false;

        $('.block-facet-blockhearing-session-year .facets-checkbox').each(function () {
          var year = $(this).attr('id').slice(-4);
          if (selectedSessionYear.toString() === year.toString() && $(this).prop('checked')) {
            foundYear = true;
          }
        });
        if (!foundYear) {
          alert('Please ensure correct session years are checked for this date range');
        }
      });
    }
  };

  Drupal.behaviors.ddfacetaccordions = {
    attach: function (context, settings) {
      var width = $(window).width();
      var searchWrapper = $('.has-facets #accordion .views-exposed-form');
      var searchHeader = $('<h2>Search</h2>');
      var toggleSidebar = $('<a>Filter Results</a>');
      var sidebarWrapper = $('#accordion');
      var sidebarElementBefore = $('#js-toggle-filter');

      if (width < 666) {

        // Add Header to the search block
        searchWrapper.once('createHeader').prepend(searchHeader);

        // Initialize accordions for facets.
        $('#accordion').accordion({
          collapsible: true,
          header: 'h2',
          heightStyle: 'content'
        });

        // Hide the sidebar.
        $(sidebarWrapper).hide();

        // Add class to toggle button.
        sidebarElementBefore.once('createtoggle').append(toggleSidebar);
        toggleSidebar.addClass('ddfacets__toggle-button');

        // Toggle function.
        $(toggleSidebar).click(function () {
          $('#accordion').toggle('slow');
          return false;
        });
      }
    }
  };
})(Drupal, jQuery);
