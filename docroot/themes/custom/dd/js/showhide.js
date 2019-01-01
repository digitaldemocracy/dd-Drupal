(function (Drupal, $) {

  'use strict';
  var showText = Drupal.t('Show');
  var hideText = Drupal.t('Hide');
  var togglerClass = '.toggler';

  Drupal.behaviors.showhide = {
    attach: function (context, settings) {
      $.fn.showHide = function (index, header_class, body_class) {
        var header = $(header_class, this).first();
        var body = $(body_class, this).first();
        var windowWidth = $(window).width();

        var showhide_expand_first_exists = false;

        $(this).closest('.layout-3col__right-sidebar, .layout-3col__left-content, .view-content').find('.view-showhide').each(function () {
          if ($(this).hasClass('showhide-expand-first')) {
            showhide_expand_first_exists = true;
          }
        });

        // If showhide-expand-first class exists, will expand 1st content group.
        if (

          $(this).hasClass('showhide-expanded') || $(this).hasClass('views-hearing-row-1') || (index === 0 && showhide_expand_first_exists && windowWidth > 666)) {
          body.show();
          header.append('<div class="toggler">' + hideText + '</div>');
          header.addClass('expanded');
          header.data('state', 1);
        }
        else {
          body.hide();
          header.append('<div class="toggler">' + showText + '</div>');
          header.addClass('collapsed');
          header.data('state', 0);
        }

        // Click handler for header.
        header.on('click', function (e) {
          var new_state = !$(this).data('state');
          // Hide all the other expanded sections.
          $(this).closest('.layout-3col__right-sidebar, .layout-3col__left-content, .layout-3col__full, .view-content').find('.view-showhide').each(function () {
            var class_header = $('.view-header', this).first();
            var class_body = $('.view-content', this).first();

            Drupal.behaviors.showhide.showHideSection(class_header, class_header, class_body, 0);

          });
          Drupal.behaviors.showhide.showHideSection($(this), header, body, new_state);
        });
      };

      // Check if legislator, expand biography is so.
      if (drupalSettings.dd_person && drupalSettings.dd_person.isLegislator) {
        $('.view-legislator-biography-years').addClass('showhide-expanded');
      }

      // Expand testimony if there is no Legislator biography.
      if (!$('.block-views-blocklegislator-biography-years-block-1').length) {
        $('.view-person-testimony-years').addClass('showhide-expanded');

      }

      // Expand Past Affiliations is block count is only 1.
      var showhideCount = 0;
      $('.layout-3col__left-content .view-showhide').each(function () {
        if (!$(this).hasClass('views-row') && $('.view-header', this).length) {
          showhideCount++;
        }
      });

      if (showhideCount === 1) {
        $('.view-person-top-block-years').addClass('showhide-expanded');
      }

      // Attach once to each view-showhide class.
      $('.view-showhide', context).once('showhide').each(function (index) {
        $(this).showHide(index, '.view-header', '.view-content');
      });

      $('.view-showhide-open-past-affiliations').on('click', function(e) {
        var class_header = $('.dd-person--classifications--former .view-header');
        var class_body = $('.dd-person--classifications--former .view-content');

        Drupal.behaviors.showhide.showHideSection(class_header, class_header, class_body, 1);
        var index = $($(this).attr('href')).index() - 1;
        $(class_body, '.ui-tabs').tabs("option", "active", index);
        $.scrollTo(class_header);
      });
    },

    showHideSection : function (element, header, body, showHide) {
      if (showHide) {
        body.show();
        element.addClass('expanded');
        element.removeClass('collapsed');
        $(togglerClass, header).text(hideText);
        element.data('state', true);
      }
      else {
        body.hide();
        element.addClass('collapsed');
        element.removeClass('expanded');
        $(togglerClass, header).text(showText);
        element.data('state', false);
      }
    }
  };
})(Drupal, jQuery);
