// constructor for a new class
// which extends VideoPlayer class
function DDVideoClipping(player) {
//alert('constructor called');
  VideoPlayer.call(this, player);
}

// inherits from VideoPlayer prototype
DDVideoClipping.prototype = Object.create(VideoPlayer.prototype, {
  // this is how you add a member variable
  myVar : {
    enumerable: true,
    configurable: true,
    writable: true,
    value: 0
  }
});

// specifies the constructor for DDVideoClipping class
DDVideoClipping.prototype.constructor = DDVideoClipping;

// This is how you override a class member method
DDVideoClipping.prototype.init = function() {
  //call super
  VideoPlayer.prototype.init.apply(this);
}

// This function is called when video players are ready
function onPlayersReady(players) {
  window.videoPlayer1 = new DDVideoClipping(players['player1']);

  //window.videoPlayer1.loadVideo(jQuery('#player1').data('videoid'));
  var startTime = parseFloat(jQuery('input[name=start]').val());
  var endTime = parseFloat(jQuery('input[name=end]').val());
  window.videoPlayer1.playVideo(startTime);

  initSlider(startTime, endTime);
  initEditButtons();
  initStartTimeFields();
  initEndTimeFields();
  
  // this function is called when response from AJAX call returns
  jQuery.fn.showHideNote = function(args) {
    setClipShare();
    setClipEmbed();
    setupTagList();
  };
}

function initEditButtons() {
  jQuery("#edit-start-button").click(function(e) {
    var startTime = parseFloat(window.videoPlayer1.getCurrentTime());
    var s_sec,s_min;
    if(startTime > 60 ){
      s_min = Math.floor(startTime/60);
      s_sec = startTime - ( s_min * 60 );
    }else{
      s_min = 0;
      s_sec = startTime; 
    }

    document.getElementById("edit-start-min").value =s_min;
    document.getElementById("edit-start-sec").value =s_sec;
    jQuery('input[name=start]').val(startTime);
    jQuery("#slider-range").slider("values", 0, startTime);
    window.videoPlayer1.playVideo();
    e.preventDefault();
  });

  jQuery("#edit-stop-button").click(function(e) {
    var endTime = parseFloat(window.videoPlayer1.getCurrentTime());
    var es_sec,es_min;
    if(endTime > 60 ){
      es_min = Math.floor(endTime/60);
      es_sec = endTime - (es_min*60);
    }else{
      es_min = 0;
      es_sec = endTime;
    } 
	
    document.getElementById("edit-end-min").value =es_min;
    document.getElementById("edit-end-sec").value =es_sec;
    jQuery('input[name=end]').val(endTime);
    jQuery("#slider-range").slider("values", 1, endTime);
    window.videoPlayer1.stopVideo();
    e.preventDefault();
  });

  jQuery("#edit-preview-button").click(function(e) {
    var startTime = parseFloat(jQuery("#edit-start-min").val()) * 60
                  + parseFloat(jQuery("#edit-start-sec").val());
    window.stopTime = parseFloat(jQuery("#edit-end-min").val()) * 60
                  + parseFloat(jQuery("#edit-end-sec").val());
    e.preventDefault();
    window.videoPlayer1.playVideo(startTime);
    window.nIntervId = setInterval(stopVideoAt, 100);
    return false;
  });
}

function stopVideoAt() {
  if (window.stopTime <=
        window.videoPlayer1.getCurrentTime()) {
    window.videoPlayer1.stopVideo();
    clearInterval(window.nIntervId);
    window.nIntervId = null;
  } else if (window.videoPlayer1.player.ended() ||
             window.videoPlayer1.player.paused()) {
    clearInterval(window.nIntervId);
  } 
}  

function initSlider(startTime, endTime) {
  var initStart = startTime;
  var initEnd = endTime;
  console.log(initStart);
  console.log(initEnd);
  jQuery("#slider-range").slider({
    range: true,
    min: 0,
    max: parseFloat(jQuery('input[name=duration]').val()),
    step: 0.1,
    values: [startTime,endTime],
    slide: function( event, ui ) {
      var timeStart = ui.values[0];
      jQuery('input[name=start]').val(timeStart);
      jQuery('#edit-start-min').val(Math.floor(timeStart/60));
      jQuery('#edit-start-sec').val(timeStart%60);
      //synch the player
      if (timeStart !== initStart) {
        initStart = timeStart;
        window.videoPlayer1.setCurrentTime(ui.values[0]);
      }

      var timeEnd = ui.values[1];
      jQuery('input[name=end]').val(timeEnd);
      jQuery('#edit-end-min').val(Math.floor(timeEnd/60));
      jQuery('#edit-end-sec').val(timeEnd%60);
      //synch the player
      if (timeEnd !== initEnd) {
        initEnd = timeEnd;
        window.videoPlayer1.setCurrentTime(ui.values[1]);
      }
    }
  });
}

function initStartTimeFields() { 
  jQuery('#edit-start-min').change(function(event) {
    var min = parseFloat(jQuery('#edit-start-min').val());
    if (!min || min < 0) {
      jQuery('#edit-start-min').val(0);
      jQuery("#slider-range").slider("values", 1, parseFloat(jQuery('#edit-end-sec').val()));
    } else {
      var time = min*60 + parseFloat(jQuery('#edit-start-sec').val());
      if (time > parseFloat(jQuery('input[name=duration]').val())) {
        time = parseFloat(jQuery('input[name=duration]').val());
        jQuery('#edit-start-min').val(Math.floor(time/60));
        jQuery('#edit-start-sec').val(time%60);
      }
      jQuery('input[name=start]').val(time);
      jQuery("#slider-range").slider("values", 0, time);
    }
  });
  
  jQuery('#edit-start-sec').change(function() {
    var sec = parseFloat(jQuery('#edit-start-sec').val());
    if (!sec || sec >= 60 || sec < 0) {
      jQuery('#edit-start-sec').val(0);
      jQuery("#slider-range").slider("values", 0, parseFloat(jQuery('#edit-start-min').val())*60);
    } else { 
      var time = parseFloat(jQuery('#edit-start-min').val())*60 + sec;
      if (time > parseFloat(jQuery('input[name=duration]').val())) {
        time = parseFloat(jQuery('input[name=duration]').val());
        jQuery('#edit-start-min').val(Math.floor(time/60));
        jQuery('#edit-start-sec').val(time%60);
      }
      jQuery('input[name=start]').val(time);
      jQuery("#slider-range").slider("values", 0, time);
    }
  });
}

function initEndTimeFields() {
  //jQuery('input[name=end]').val(jQuery("#slider-range").slider( "values", 1));
  jQuery('#edit-end-min').change(function(event) {
    var min = parseFloat(jQuery('#edit-end-min').val());
    if (!min || min < 0) {
      jQuery('#edit-end-min').val(0);
      jQuery("#slider-range").slider("values", 1, parseFloat(jQuery('#edit-end-sec').val()));
    } else {
      var time = min*60 + parseFloat(jQuery('#edit-end-sec').val());
      if (time > parseFloat(jQuery('input[name=duration]').val())) {
        time = parseFloat(jQuery('input[name=duration]').val());
        jQuery('#edit-end-min').val(Math.floor(time/60));
        jQuery('#edit-end-sec').val(time%60);
      }
      jQuery('input[name=end]').val(time);
      jQuery("#slider-range").slider("values", 1, time);
    }
  });
  
  jQuery('#edit-end-sec').change(function() {
    var sec = parseFloat(jQuery('#edit-end-sec').val());
    if (!sec || sec >= 60 || sec < 0) {
      jQuery('#edit-end-sec').val(0);
      jQuery("#slider-range").slider("values", 1, parseFloat(jQuery('#edit-end-min').val())*60);
    } else { 
      var time = parseFloat(jQuery('#edit-end-min').val())*60 + sec;
      if (time > parseFloat(jQuery('input[name=duration]').val())) {
        time = parseFloat(jQuery('input[name=duration]').val());
        jQuery('#edit-end-min').val(Math.floor(time/60));
        jQuery('#edit-end-sec').val(time%60);
      }
      jQuery('input[name=end]').val(time);
      jQuery("#slider-range").slider("values", 1, time);
    }
  });
}

function format_date(time) {
  // Hours, minutes and seconds
  var hrs = ~~(time / 3600);
  var mins = ~~((time % 3600) / 60);
  var secs = time % 60;
  // Output like "1:01" or "4:03:59" or "123:03:59"
  ret = "";
  
  if (hrs > 0)
    ret += "" + hrs + ":" + (mins < 10 ? "0" : "");

  ret += "" + mins + ":" + (secs < 10 ? "0" : "");
  ret += "" + secs;
  
  return ret;
}

/**
 * load the clip list on the right hand side.
 */
function clip_gallery_ajax_load() {
  jQuery("#clipper-gallery-block")
    .load("/dd_video_editor/my_clip_bank_block", function() {
      setClipShare();
      setClipEmbed();
    });
}

/**
 * load the clip list on the right hand side
 * when the user comes back to the page
 * with browser's back button.
 */
function load_clip_gallery_on_backbutton() {
  var refreshed = jQuery("#refreshed").val();
  console.log(refreshed);
  if (refreshed === "no") {
    jQuery("#refreshed").val("yes");
  } else {
    //location.reload();
    clip_gallery_ajax_load();
  }
}

jQuery(document).ready(function(jQuery){
  //load_clip_gallery_on_backbutton();
  jQuery.fn.appendTranscript = function(utters) {
    utters = JSON.parse(utters);
    var transcript = '\n\n---TRANSCRIPT---';
    console.log(utters);
    utters.forEach(function(utter) {
      transcript += '\n\n' + utter.speaker + ': ' + utter.text;
    });
    CKEDITOR.instances['edit-commentary-value'].insertText(transcript);
  }
});
