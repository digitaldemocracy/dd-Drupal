/* eslint-disable strict */
/* eslint-disable no-console */

var UtteranceLoader = (function () {
  var PREV_MODE = 0;
  var NEXT_MODE = 1;
  var JUMP_MODE = 2;
  var spinnerTmpl = '<div class="spinner">'
    + '<div class="bounce1"></div>'
    + '<div class="bounce2"></div>'
    + '<div class="bounce3"></div>'
    + '</div>';

  // Constructs new UtteranceLoader. Sets up [container] to lazy load
  // [hid] hearing utterances, segmented by video in [vids]
  var UtteranceLoader = function (hid, vids, container) {
    var that = this;
    this.hid = hid;
    this.vids = vids;
    this.loaded = {};
    this.container = jQuery(container);
    this.container.wrap('<div style="position:relative;"></div>');
    jQuery('<div id="header-message"></div>').insertBefore(this.container.parent());

    // Create loading overlay
    this.spinner = jQuery(spinnerTmpl).prependTo(this.container.parent()).css('width', this.container.css('width'));
    // Create Top "load more"
    this.topUtter = jQuery('<div></div>').addClass('loader-div')
      .html('Load More').appendTo(this.container);
    // Create div where view is loaded
    this.target = jQuery('<div></div>').appendTo(this.container);
    // Create Bottom "load more"
    this.bottomUtter = jQuery('<div></div>').addClass('loader-div')
      .html('Load More').appendTo(this.container);

    // Scroll listener
    this.container.on('scroll', function (e) {
      var elem = jQuery(e.currentTarget);
      var index;
      // Reached bottom
      if (elem[0].scrollHeight - elem.scrollTop() === elem.outerHeight()) {
        index = parseInt(that.target.attr('data-next-vid'));
        if (index < that.vids.length) {
          that.loadMoreVideoUtters(index, NEXT_MODE);
        }
      }
      // Reached top
      else if (elem.scrollTop() === 0) {
        index = parseInt(that.target.attr('data-prev-vid'));
        if (index >= 0) {
          that.loadMoreVideoUtters(index, PREV_MODE);
        }
      }
    });
  };

  // Get Utterances for vids[index]
  UtteranceLoader.prototype.loadVideoUtters = function (index, callback) {
    var utters = this.loaded[this.vids[index]];
    // is vid utters already loaded?
    if (utters) {
      callback(utters);
    }
    // Otherwise load through AJAX
    else {
      this.spinner.show();
      // AJAX load view for [hid]/[vid]
      jQuery.post(drupalSettings.path.baseUrl + 'views/ajax', {
        view_name: 'utterance',
        view_display_id: 'block_1',
        view_args: this.hid + '/' + this.vids[index]
      }, function (response) {
        // Response[3] is the element with the <div> for the view.
        if (response[3] !== undefined) {
          callback(response[3]);
        }
      });
    }
  }
  // Load more utters and add to container
  UtteranceLoader.prototype.loadMoreVideoUtters = function (index, mode) {
    var that = this;
    this.loadVideoUtters(index, function (response) {
      //get only the table rows for view content excluding the header
      var utters = jQuery(response.data).find('tr.utterance');
      var scrollTarget;
      that.spinner.hide();
      if (mode === PREV_MODE) {
        scrollTarget = utters.last();
        that.target.find('tbody').prepend(utters);
      }
      else if (mode === NEXT_MODE) {
        that.target.find('tbody').append(utters);
      }
      else if (mode === JUMP_MODE) {
        scrollTarget = that.target;
        that.target.find('tbody').empty();
        that.target.find('tbody').append(utters);
      }
      that.updateVids(index, mode);
      // Scroll to scroll target, if there is one
      if (scrollTarget) {
        that.container.scrollTo(scrollTarget);
      }
      // 'Cache' responses
      that.loaded[that.vids[index]] = response;
      if (that.callback) {
        that.callback();
      }
    });
  };
  // Load utters for specific vid. Loaded by outside event
  UtteranceLoader.prototype.jumpTo = function (index) {
    var next = parseInt(this.target.attr('data-next-vid'));
    var prev = parseInt(this.target.attr('data-prev-vid'));
    // Is utterance not already visible?
    if (!(index > prev && index < next)) {
      this.loadMoreVideoUtters(index, JUMP_MODE);
    }
  };
  // Loads videos for the first time
  UtteranceLoader.prototype.loadStartVideoUtters = function (index) {
    var that = this;
    this.loadVideoUtters(index, function (response) {
      that.spinner.hide();
      //move header to header-message area
      var header = jQuery(response.data).find('div.view-header');
      if (header.length)
        jQuery('#header-message').html(header);

      //put the whole block in the target area
      that.target.html(response.data);
      //hide the header in the target area
      that.target.find('.view-header').hide();

      if (that.target.find('.view').children().length) {
        that.updateVids(index, JUMP_MODE);
        that.container.scrollTo(that.target);
      }
      else {
        that.container.html('Transcript coming soon...').css('height', '2em');
      }
      that.loaded[that.vids[index]] = response;
      if (that.callback) {
        that.callback();
      }
    });
  };
  // Updates data params
  UtteranceLoader.prototype.updateVids = function (index, mode) {
    if (mode === JUMP_MODE || mode === NEXT_MODE) {
      this.target.attr('data-next-vid', index + 1);
      if (index !== this.vids.length - 1) {
        this.bottomUtter.show();
      }
      else {
        this.bottomUtter.hide();
      }
    }
    if (mode === JUMP_MODE || mode === PREV_MODE) {
      this.target.attr('data-prev-vid', index - 1);
      if (index !== 0) {
        this.topUtter.show();
      }
      else {
        this.topUtter.hide();
      }
    }
  };

  return UtteranceLoader;
})();
