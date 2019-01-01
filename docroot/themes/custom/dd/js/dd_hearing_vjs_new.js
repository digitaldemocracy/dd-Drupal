/* eslint-disable strict */
/* eslint-disable no-console */
var VideoPlayer;
var Popcorn;
var videojs;
var UtteranceLoader;
var AgendizedBillsLoader;
var google;

window.findMarker = null;

function DDHearing(player) {
  VideoPlayer.call(this, player);
}

DDHearing.prototype = Object.create(VideoPlayer.prototype, {
  currIndex: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  videos: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  paramVid: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  paramTime: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: 0
  },
  currHighLighted: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  currBill: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  urlParams: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: null
  },
  set: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: 0
  },
  recursion: {
    enumerable: true,
    configurable: true,
    writable: true,
    value: 0
  }
});

// callback function for jumpig to a position in the video when video is downloaded
DDHearing.prototype.playWhenReady = function (offset) {
  console.log('offset=' + offset);
  console.log(this.player.bufferedPercent());
  this.recursion++;
  if (this.player.bufferedPercent() > 0 || this.recursion > 9) {
    this.player.currentTime(offset);
  }
  else {
    setTimeout(function () {
      window.videoPlayer.playWhenReady(offset);
    }, 1000);
  }
};

// helper function to play a video from the offset
DDHearing.prototype.playVideo = function (offset) {
  this.player.play();
  console.log(this.player.readyState());
  console.log('offset=' + offset);
  if (this.isChrome || this.player.readyState() > 2 || offset === 0) {
    this.player.currentTime(offset);
    console.log('ready');
  }
  else {
    console.log('not ready');
    setTimeout(function () {
      window.videoPlayer.playWhenReady(offset);
    }, 1500);
  }
};

/**
 * play the next video
 */
DDHearing.prototype.playNextVideo = function () {
  if (this.currIndex < this.videos.length - 1) {
    this.currIndex++;
    this.loadVideo(this.videos[this.currIndex]);
    this.utters.jumpTo(this.currIndex);
    this.playVideo(0);
  }
};

DDHearing.prototype.getCurrentVideo = function () {
  return this.videos[this.currIndex];
};

DDHearing.prototype.getVideoWithTime = function () {
  return 'startTime=' + this.getCurrentTime() + '&vid=' + this.getCurrentVideo();
};

DDHearing.prototype.getVideoId = function () {
  var url = this.player.currentSrc();
  var params = url.split('?')[1].split('&');
  for (var i = 0; i < params.length; i++) {
    if (params[i].search('v=') >= 0) {
      return params[i].split('v=')[1];
    }
  }
  return null;
};

// helper function to constract list of youtubeid
DDHearing.prototype.init = function () {
  console.log('calling init');
  // call super
  VideoPlayer.prototype.init.apply(this);
  console.log(this.isChrome);

  var that = this;
  var components = null;
  var params = {vid: null, startTime: 0};
  this.urlParams = window.location.search.substring(1).split('&');
  if (this.urlParams[0]) {
    for (var i = 0; i < this.urlParams.length; i++) {
      components = this.urlParams[i].split('=');
      if (components[0]) {
        params[components[0]] = components[1];
      }
    }
  }
  this.paramTime = params['startTime'];
  this.paramVid = params['vid'];

  var ids = jQuery('#dd-videos');
  if (ids && ids.length > 0) {
    this.videoUrlPath = ids.data('videourlpath');
    this.videos = ids.data('youtubeid').split(',');
    this.currIndex = (this.paramVid ? this.videos.indexOf(this.paramVid) : 0);
    this.paramVid = this.videos[this.currIndex];

    // Determine HID from path.
    var path_components = drupalSettings.path.currentPath.split('/');
    var hid = path_components[1];

    // Set up utterance table
    this.utters = new UtteranceLoader(hid, this.videos, '#utterances');
    this.utters.callback = setupUtterEvents;
    // Sets up pop events when new video is loaded
    this.player.on('loadedmetadata', function () {
      // Destroy current videoPlayer
      if (that.pop) {
        Popcorn.destroy(that.pop);
      }
      that.pop = Popcorn('#' + jQuery(this.el_).find('video').attr('id'));
      that.utters.callback();
      //setupBillEvents();
      // Set up Aggendized Bills block
      window.bills = new AgendizedBillsLoader(hid, '#agendized-bills');
      window.bills.callback = setupBillEvents;
      window.bills.loadAgendizedBills();
    });
    this.utters.loadStartVideoUtters(this.currIndex);
    if (typeof (drawMap) === typeof (Function)) {
      drawMap();
    }
  }

  this.playerReady = true;
  this.loadVideo(this.paramVid);
  this.playVideo(this.paramTime);
};

DDHearing.prototype.constructor = DDHearing;

// Utterance popcorn plugin
Popcorn.plugin('utterance', function () {
  return {
    // Called when start timeEvent occurs
    start: function (event, track) {
      // Highlight utterance
      track.utter.addClass('highlighted');
      // Scroll to utterance
      track.container.scrollTo(track.utter);
      // Select speaker pip on district map
      var speakerPid = track.utter.find('.utterance-text').data('pid');
      if (speakerPid && window.findMarker) {
        try {
          window.findMarker(speakerPid);
        } catch(e) {
          console.log(e);
        }
      }
    },
    // Called when end timeEvent occurs
    end: function (event, track) {
      // remove highlight
      track.utter.removeClass('highlighted');
    }
  };
});

// Bill popcorn plugin
Popcorn.plugin('bill', function () {
  return {
    // Called when start timeEvent occurs
    start: function (event, track) {
      // highlight bill and show bill votes
      track.bill.addClass('highlighted');
      track.votes.removeClass('hide');
      // Scroll to utterance
      track.container.scrollTo(track.bill);

    },
    // Called when end timeEvent occurs
    end: function (event, track) {
      // unhighlight bill and hide bill votes
      track.bill.removeClass('highlighted');
      track.votes.addClass('hide');
    }
  };
});

// Sets up events for Bills
function setupBillEvents() {
  var duration = window.videoPlayer.player.duration();
  // Setup bill TrackEvents for curVid
  jQuery('.bill').each(function () {
    var text = jQuery(this).find('div.bill-text');
    // Index of first video that bill occurs in
    var startIndex = window.videoPlayer.videos.indexOf(text.data('youtubeid'));
    // Index of last video that bill occurs in
    var endIndex = window.videoPlayer.videos.indexOf(text.data('endyoutubeid'));
    // If bill could be discussed durring current video
    if (startIndex <= window.videoPlayer.currIndex && endIndex >= window.videoPlayer.currIndex) {
      // setup popcorn.bill object
      var start = text.data('starttime');
      var end = text.data('endtime');
      if (startIndex < window.videoPlayer.currIndex) {
        start = 0;
      }
      if (endIndex > window.videoPlayer.currIndex) {
        end = duration;
      }
      end = Math.min(end, duration);  // Make sure end of track doesn't go over duration of video
      window.videoPlayer.pop.bill({
        start: start,
        end: end,
        bill: jQuery(this),
        votes: jQuery('div.bill_votes.' + text.data('bid')),
        container: jQuery('#block-hearingagendizedbillsblock .view-content').first()

      });
    }
  });
  // set up bill table
  jQuery('#block-hearingagendizedbillsblock').on('click', 'div.bill-text', timeLinkClicked);
}

// Sets up events for utterances
function setupUtterEvents() {
  var pop = window.videoPlayer.pop;
  if (!pop) {
    return;
  }
  // Get all utterance TrackEvents
  var tracks = pop.getTrackEvents().filter(function (track) {
    return track._natives.type === 'utterance';
  });
  // Check to see if utterance tracks are from curVid
  if (tracks[0] && tracks[0].vid === window.videoPlayer.videos[window.videoPlayer.currIndex]) {
    return;
  }
  // Add tracks for all utterances for curVid
  var duration = window.videoPlayer.player.duration();
  jQuery('.utterance').each(function () {
    var text = jQuery(this).find('.utterance-text');
    if (text.data('youtubeid') === window.videoPlayer.videos[window.videoPlayer.currIndex]) {
      var start = text.data('starttime');
      var end = Math.min(text.data('endtime'), duration);   // Make sure end of track doesn't go over duration of video
      pop.utterance({
        start: start,
        end: end > start ? end : start + 0.5,  // Each utterance is highlighted for a minimum of .5 seconds
        utter: jQuery(this),
        container: jQuery('#utterances'),
        vid: text.data('youtubeid')
      });
    }
  });
}

function timeLinkClicked() {
  var vid = jQuery(this).data('youtubeid');
  if (vid !== window.videoPlayer.videos[window.videoPlayer.currIndex]) {
    window.videoPlayer.currIndex = window.videoPlayer.videos.indexOf(vid);
    window.videoPlayer.utters.jumpTo(window.videoPlayer.currIndex);
    window.videoPlayer.loadVideo(vid);
  }
  window.videoPlayer.playVideo(jQuery(this).data('starttime'));
}


var setupGoogleCharts = function () {
  google.load('visualization', '1', {
    callback: drawPrimaryChart,
    packages: ['corechart']
  });

  // Determine which hid the current hearing corresponds to
  var hid = document.URL.split('/hearing/')[1];
  if (isNaN(hid)) {
    hid = hid.split('?')[0];
  }

  // Parse the current JSON data on the page with stats info
  var local = jQuery.parseJSON(jQuery('.stats').html().replace(';', ''));
  local = local['stats'];

  var dataTable = [];
  var max = {words: 0, index: -1};
  var i = 0;

  // Iterate through each person in the JSON data
  for (var pid in local) {
    if (local[pid] !== 'None') {
      // Keep track of max words in order to delete the committee chair
      if (local[pid][1] > max.words) {
        max.words = local[pid][1];
        max.index = i;
      }
      // Push the time, word count, and tooltip data which contains the
      // avg/cur participation percentage to the dataTable
      // Also, set the color to red if cur rate exceeds avg rate
      var currHearing = 'N/A';
      var avgHearing = 'N/A';
      currHearing = local[pid][hid][2].toFixed(2).toString();
      avgHearing = local[pid]['total'].toFixed(2).toString();
      dataTable.push([local[pid][hid][0], local[pid][hid][1],
        local[pid]['name'] + "\nCurrent Hearing Participation: " + currHearing +
        "\nAverage Hearing Participation: " + avgHearing,
        currHearing > avgHearing ? 'point {fill-color: #FE2E2E}' : null]);
      i++;
    }
  }

  // Take out comittee chair
  if (max.index !== -1) {
    dataTable.splice(max.index, 1);
  }

  // Determine which options are set for the chart
  var primaryOptions = {
    title: 'Speakers Participation for Current Hearing',
    vAxis: {title: 'Words'},
    hAxis: {title: 'Time'},
    legend: 'none',
    tooltip: {showColorCode: true, trigger: 'both'},
    explorer: {actions: ['dragToZoom', 'rightClickToReset'], maxZoomIn: '0.05'},
    crosshair: {trigger: 'selection'}
  };

  var data;
  var primaryChart;

  function drawPrimaryChart() {
    // Initialize table's axes, data, HTML container, and chart type
    data = new google.visualization.DataTable();
    data.addColumn('number', 'Time');
    data.addColumn('number', 'Words');
    data.addColumn({type: 'string', role: 'tooltip'});
    data.addColumn({type: 'string', role: 'style'});

    data.addRows(dataTable);

    var visibleDiv = document.getElementById('chart_div');
    primaryChart = new google.visualization.ScatterChart(visibleDiv);

    // Draw table
    primaryChart.draw(data, primaryOptions);
  }

  // Function which selects the current speaker speaking in the hearing
  var findSpeaker = function (name) {
    for (var i = 0; i < dataTable.length; i++) {
      var speaker = dataTable[i][2].split('\n')[0].trim().toLowerCase().split(/[\s,]+/);
      if (speaker[0] === name[1] && speaker[1] === name[0]) {
        primaryChart.setSelection([{row: i, column: null}]);
        return;
      }
    }
    primaryChart.setSelection([]);
  };
};

function onPlayersReady(players) {
  window.videoPlayer = new DDHearing(players['player']);
  window.videoPlayer.on('playing', onPlayerPlaying)
    .on('ended', onPlayerEnded);
}

function onPlayerPlaying() {
  if (!window.videoPlayer.playing) {
    window.videoPlayer.playing = true;
  }
}

function onPlayerEnded() {
  console.log('player ended');
  window.videoPlayer.playing = false;
  window.videoPlayer.playNextVideo();
}

// code to manipulate DOM when the page is playerReady (onLoad) goes in here
jQuery(document).ready(function () {
  // set up speaker name table
  jQuery('#block-views-block-hearing-speakers-block-1').on('click', 'a', timeLinkClicked);
  // set up utterance table
  jQuery('#utterances').on('click', 'div.utterance-text', timeLinkClicked);
  // set up bill table
  //jQuery('#block-views-block-hearing-components-agendized-bills').on('click', 'div.bill-text', timeLinkClicked);

  // set up videojs players
  var players = null;
  jQuery('video').each(function () {
    var id = jQuery(this).attr('id');
    if (id) {
      videojs(id, {}, function() {});
    }
    players = videojs.players;
  });
  if (!jQuery.isEmptyObject(players) && typeof onPlayersReady == 'function') {
    onPlayersReady(players);
  }

  jQuery('a.dd-share-toolbar-group-item.dd-share-toolbar-video-clip').click(function() {
    this.href += '&' + window.videoPlayer.getVideoWithTime();
    console.log(this.href);
  });
});


// Number of chars to display before and after the key word
// Trim Bill Digest text to some chars around the key word
var trimBillDigest = function(term) {
  var NUM_CHAR = 100;

  if (term != null && term.length > 0) {
    jQuery('body.page-bills div.col-digest').each(function () {
      if (jQuery(this).find('a').length !== 0) {
        return;
      }
      var sIdx = jQuery(this).text().toLowerCase().indexOf(term.toLowerCase());
      var eIdx = sIdx + term.length;
      var from = sIdx - NUM_CHAR;
      var text = '';
      // if there are more than NUM_CHAR chars before the key word, add ...
      if (from > 0) {
        text = '... ' + text;
      }
      text = text + jQuery(this).text().substring(from, sIdx)
        + '<strong>' + jQuery(this).text().substr(sIdx, term.length) + '</strong>'
        + jQuery(this).text().substr(eIdx, NUM_CHAR);
      // if there are more than NUM_CHAR chars after the key word, add ...
      if (eIdx + NUM_CHAR < jQuery(this).text().length) {
        text = text + ' ...';
      }
      jQuery(this).html(text);
    });
  }
  else {
    jQuery('body.page-bills div.col-digest').each(function () {
      if (jQuery(this).text().length > NUM_CHAR + NUM_CHAR) {
        jQuery(this).html(jQuery(this).text()
            .substring(0, NUM_CHAR + NUM_CHAR) + ' ...');
      }
    });
  }
};
