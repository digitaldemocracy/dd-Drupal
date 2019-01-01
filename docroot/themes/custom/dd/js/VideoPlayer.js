/* eslint-disable strict */
/* eslint-disable no-console */

window.VIDEOJS_NO_DYNAMIC_STYLE = false;

/**
 * Declare custom math function for rounding
 */
var myMath = {};

myMath.round = function (number, precision) {
  var factor = Math.pow(10, precision);
  var tempNumber = number * factor;
  var roundedTempNumber = Math.round(tempNumber);
  return roundedTempNumber / factor;
};

/**
 * Base class which wraps HTML5/VideoJs video player.
 * for more info please see http://docs.videojs.com/docs/api/player.html
 *
 * Do not modify this file.
 * Extend this class in a separate file if you need to alter this class.
 */

/**
 * @constructor
 * @param {object} player VideoJs player object.
 */
function VideoPlayer(player) {
  this.player = player;
  this.init();
}

/**
 * push properties to prototype
 */
VideoPlayer.prototype = {
  player: null,
  playerReady: false,
  playing: false,
  videoUrlPath: null,
  isOpera: false,
  isFireFox: false,
  isSafari: false,
  isChrome: false,
  isIE: false
};

/**
 * adds event handler.
 * @param {string} evt event name
 * @param {function} func event handler function
 * @return {object} this
 */
VideoPlayer.prototype.on = function (evt, func) {
  return this.player.on(evt, func);
};

/**
 * loads video specified by the videoid/youtubeid.
 * @param {string} videoid file id of the video
 */
VideoPlayer.prototype.loadVideo = function (videoid) {
  console.log(videoid);
  this.player.poster(this.videoUrlPath + videoid + '/thumbnails/' + 'large.jpg');

  this.player.src(
    [{
      type: 'video/mp4',
      src: this.videoUrlPath + videoid + '/' + videoid + '.mp4',
      codecs: 'avc1.4D401E, mp4a.40.2'
    },
    {
      type: 'video/webm',
      src: this.videoUrlPath + videoid + '/' + videoid + '.webm'
    }]);

  console.log(this.player.currentSrc());
  console.log(this.player.currentType());
  console.log(this.player.networkState());
  this.player.load();
};

/**
 * set the current time of the video (skip the video to the offset)
 * @param {number} offset offset from the start of the video in second
 */
VideoPlayer.prototype.setCurrentTime = function (offset) {
  this.player.currentTime(offset);
};

/**
 * set the source of video
 * @param {string} json_data json object
 */
VideoPlayer.prototype.setSource = function (json_data) {
  this.player.src(json_data);
};

/**
 * play the video from the offset if given
 * @param {number} offset offset from the start of the video in second
 */
VideoPlayer.prototype.playVideo = function (offset) {
  if (offset || offset === 0) {
    this.player.currentTime(offset);
  }
  this.player.play();
};

/**
 * stop the video
 */
VideoPlayer.prototype.stopVideo = function () {
  this.player.pause();
};

/**
 * play back the video from the start
 */
VideoPlayer.prototype.playbackVideo = function () {
  this.player.currentTime(0);
  this.player.play();
};

/**
 * get the readyState of the player.
 * for more info please see http://docs.videojs.com/docs/api/player.html
 * @param {number} offset offset from the start of the video in second
 * @return {number} readyState of the player
 */
VideoPlayer.prototype.getPlayerState = function () {
  return this.player.readyState();
};

/**
 * check if the player is paused.
 * @return {boolean} if the player is paused or not
 */
VideoPlayer.prototype.paused = function () {
  return this.player.paused();
};

/**
 * get the current time of the video
 * @return {number} offset offset from the start in second.
 *                        number after decimal point is igonored.
 */
VideoPlayer.prototype.getCurrentTime = function () {
  return myMath.round(this.player.currentTime(), 1);
};

/**
 * get the duration of the video
 * @return {number} the duration of the video in second.
 *                  number after decimal point is ignored.
 */
VideoPlayer.prototype.getDuration = function () {
  return myMath.round(this.player.duration(), 1);
};

/**
 * initialize the object
 * The type of browser is checked and
 *   videoUrlPath is initialized with the url of s3
 */
VideoPlayer.prototype.init = function () {
  this.isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
  this.isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
  this.isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
  this.isChrome = !!window.chrome && !this.isOpera;              // Chrome 1+
  this.isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
  this.videoUrlPath = 'https://videostorage-us-west.s3.amazonaws.com/videos/';
};

