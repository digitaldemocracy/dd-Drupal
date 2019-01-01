// constructor for a new class
// which extends VideoPlayer class
function DdBlogPlayer(player) {
//alert('constructor called');
  VideoPlayer.call(this, player);
}

// inherits from VideoPlayer prototype
DdBlogPlayer.prototype = Object.create(VideoPlayer.prototype, {
  // this is how you add a member variable
  myVar : {
    enumerable: true,
    configurable: true,
    writable: true,
    value: 0
  }
});

// specifies the constructor for DdBlogPlayer class
DdBlogPlayer.prototype.constructor = DdBlogPlayer;

// This is how you override a class member method
DdBlogPlayer.prototype.init = function() {
  //call super
  VideoPlayer.prototype.init.apply(this);

  var that = this;
  this.paramTime = parseInt(jQuery('input[name="start"]').val());
  this.paramVid = jQuery('input[name="videoid"]').val();
  var ids = jQuery('#dd-videos');
  if (ids && ids.length > 0) {
    this.videoUrlPath = ids.data('videourlpath');
    this.videos =  ids.data('youtubeid').split(",");
    this.currIndex = 0;
    this.paramVid = this.videos[this.currIndex];
  }

  this.playerReady = true;
  this.loadVideo(this.paramVid);
  this.playVideo(this.paramTime);

}

// This function is called when video players are ready
function onPlayersReady(players) {
  window.videoPlayer1 = new DdBlogPlayer(players['player1']);

  //window.videoPlayer1.loadVideo(jQuery('#player1').data('videoid'));
  var startTime = parseInt(jQuery('input[name="start"]').val());
  var endTime = parseInt(jQuery('input[name="end"]').val());
  console.log(startTime);
  console.log(jQuery('input[name="start"]').val());
  window.videoPlayer1.setCurrentTime(startTime);

  console.log(jQuery('input[name="duration"]').val());
  initSlider(startTime, endTime);
  initEditButtons();
  initStartTimeFields();
  initEndTimeFields();
}

function initEditButtons() {
  jQuery("#edit-start-button").click(function(e) {
    var startTime = window.videoPlayer1.getCurrentTime();
    jQuery('input[name="field_start_time[0][value]"]').val(startTime);
    var s_sec,s_min;
    if(startTime > 60 ){
      s_min = parseInt(startTime/60);
      s_sec = startTime - ( s_min * 60 );
    }else{
      s_min = 0;
      s_sec = startTime; 
    }

    document.getElementById("edit-start-min").value =s_min ;
    document.getElementById("edit-start-sec").value =s_sec ;
    jQuery("#slider-range").slider("values", 0, startTime);
    window.videoPlayer1.playVideo();
    e.preventDefault();
  });

  jQuery("#edit-stop-button").click(function(e) {
    var endTime = window.videoPlayer1.getCurrentTime();
    jQuery('input[name="field_end_time[0][value]"]').val(endTime);
    var es_sec,es_min;
    if(endTime > 60 ){
      es_min = parseInt(endTime/60);
      es_sec = endTime - (es_min*60);
    }else{
      es_min = 0;
      es_sec = endTime;
    } 
	
    document.getElementById("edit-end-min").value =es_min ;
    document.getElementById("edit-end-sec").value =es_sec ;
    jQuery("#slider-range").slider("values", 1, endTime);
    window.videoPlayer1.stopVideo();
    e.preventDefault();
  });

  jQuery("#edit-preview-button").click(function(e) {
    var startTime = parseInt(jQuery("#edit-start-min").val()) * 60
                  + parseInt(jQuery("#edit-start-sec").val());
    window.stopTime = parseInt(jQuery("#edit-end-min").val()) * 60
                  + parseInt(jQuery("#edit-end-sec").val());
    e.preventDefault();
    window.videoPlayer1.playVideo(startTime);
    window.nIntervId = setInterval(stopVideoAt, 1000);
    return false;
  });
}

function stopVideoAt() {
  //console.log(window.stopTime); 
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
    max: parseInt(jQuery('input[name="duration"]').val()),
    step: 1,
    values: [startTime,endTime],
    slide: function( event, ui ) {
      var timeStart = ui.values[0];
      jQuery('input[name="field_start_time[0][value]"]').val(timeStart);
      jQuery('#edit-start-min').val(Math.floor(timeStart/60));
      jQuery('#edit-start-sec').val(timeStart%60);
      //synch the player
      if (timeStart !== initStart) {
        initStart = timeStart;
        window.videoPlayer1.setCurrentTime(ui.values[0]);
      }

      var timeEnd = ui.values[1];
      jQuery('input[name="field_end_time[0][value]"]').val(timeEnd);
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
    var min = Math.round(parseInt(jQuery('#edit-start-min').val()));
    if (!min || min < 0) {
      jQuery('#edit-start-min').val(0);
      jQuery("#slider-range").slider("values", 1, parseInt(jQuery('#edit-end-sec').val()));
    } else {
      var time = min*60 + parseInt(jQuery('#edit-start-sec').val());
      if (time > parseInt(jQuery('input[name="duration"]').val())) {
        time = parseInt(jQuery('input[name="duration"]').val());
        jQuery('#edit-start-min').val(Math.floor(time/60));
        jQuery('#edit-start-sec').val(time%60);
      }
      jQuery('input[name="field_start_time[0][value]"]').val(time);
      jQuery("#slider-range").slider("values", 0, time);
    }
  });
  
  jQuery('#edit-start-sec').change(function() {
    var sec = Math.round(parseInt(jQuery('#edit-start-sec').val()));
    if (!sec || sec >= 60 || sec < 0) {
      jQuery('#edit-start-sec').val(0);
      jQuery("#slider-range").slider("values", 0, parseInt(jQuery('#edit-start-min').val())*60);
    } else { 
      var time = parseInt(jQuery('#edit-start-min').val())*60 + sec;
      if (time > parseInt(jQuery('input[name="duration"]').val())) {
        time = parseInt(jQuery('input[name="duration"]').val());
        jQuery('#edit-start-min').val(Math.floor(time/60));
        jQuery('#edit-start-sec').val(time%60);
      }
      jQuery('input[name="field_start_time[0][value]"]').val(time);
      jQuery("#slider-range").slider("values", 0, time);
    }
  });
}

function initEndTimeFields() {
  jQuery('#edit-end-min').change(function(event) {
    var min = Math.round(parseInt(jQuery('#edit-end-min').val()));
    if (!min || min < 0) {
      jQuery('#edit-end-min').val(0);
      jQuery("#slider-range").slider("values", 1, parseInt(jQuery('#edit-end-sec').val()));
    } else {
      var time = min*60 + parseInt(jQuery('#edit-end-sec').val());
      if (time > parseInt(jQuery('input[name="duration"]').val())) {
        time = parseInt(jQuery('input[name="duration"]').val());
        jQuery('#edit-end-min').val(Math.floor(time/60));
        jQuery('#edit-end-sec').val(time%60);
      }
      jQuery('input[name="field_end_time[0][value]"]').val(time);
      jQuery("#slider-range").slider("values", 1, time);
    }
  });
  
  jQuery('#edit-end-sec').change(function() {
    var sec = Math.round(parseInt(jQuery('#edit-end-sec').val()));
    if (!sec || sec >= 60 || sec < 0) {
      jQuery('#edit-end-sec').val(0);
      jQuery("#slider-range").slider("values", 1, parseInt(jQuery('#edit-end-min').val())*60);
    } else { 
      var time = parseInt(jQuery('#edit-end-min').val())*60 + sec;
      if (time > parseInt(jQuery('input[name="duration"]').val())) {
        time = parseInt(jQuery('input[name="duration"]').val());
        jQuery('#edit-end-min').val(Math.floor(time/60));
        jQuery('#edit-end-sec').val(time%60);
      }
      jQuery('input[name="field_end_time[0][value]"]').val(time);
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

jQuery(document).ready(function(jQuery){
  var players = null;
  if (typeof videojs !== 'undefined') {
    jQuery("video").each(function () {
      var id = jQuery(this).attr("id");
      if (id) {
        videojs(id, {}, function() {});
      }
      players = videojs.getPlayers();
    });
  }
  if (!jQuery.isEmptyObject(players) && typeof onPlayersReady == 'function') {
    onPlayersReady(players);
  }
});
