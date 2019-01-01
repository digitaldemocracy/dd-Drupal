var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var ddVideoPlayer = null;
var videoSettings = {}

function onYouTubeIframeAPIReady(){
//  jQuery('a.js-about-us-video').css('visibility', 'visible').filter('.default').click();
  jQuery('a.js-about-us-video').css('visibility', 'visible');
}

function onPlayerStateChange(e){
  if(e.data == YT.PlayerState.ENDED){
    videoPlayed(e.target.B.videoData);
    updateShareVideoOverlay(e.target.B.videoData);
  }
}

function playVideo(videoUrl){
  if(ddVideoPlayer == null){
    var defaultVideoId = getVideoId(videoUrl);
    ddVideoPlayer = new YT.Player('main-video', {
                              width: jQuery('.video-section').width(),
                              height: jQuery('.video-section').height(),
                              videoId: defaultVideoId,
                              events: {
                                'onStateChange': onPlayerStateChange
                              }});
                              
  }else{
    ddVideoPlayer.stopVideo();
    ddVideoPlayer.loadVideoByUrl(videoUrl);
    ddVideoPlayer.playVideo();
  }
}

function getVideoId(videoUrl){
  var arrUrlComponents = (new String(videoUrl)).split('/');
  return arrUrlComponents[arrUrlComponents.length - 1];
}

function videoPlayed(videoData){
  updateShareVideoOverlay(videoData);
  displayShareVideoOverlay();
}

function aboutList(){
  var lnk = jQuery('ul.about li').find('h3');

  var jumpPoint = window.location.hash;
    
  if (lnk.length > 0){
  
    if (((jumpPoint == undefined) || (jumpPoint == '')) && (jumpPoint != '#in-the-news') && (jumpPoint != '#endorsements')) {  
      jQuery('ul.about li:first').addClass('selected');
    }
     jQuery('ul.about li:nth-child(2n) h3').addClass('grey');
    
      lnk.click(function(){
        if (!jQuery(this).parent().hasClass('selected')){
            jQuery(this).parent().addClass('selected');
            jQuery(this).parent().siblings().removeClass('selected');
            
            if (jQuery(this).next().find('.advisory-board').length > 0){
               maxHeight();
              }
            
          }
          else{
            jQuery(this).parent().removeClass('selected');
            }        
        
          jQuery(window).scrollTop(jQuery(this).offset().top);
          
        
        });
        
      
  }
  
  var jumpPoint = window.location.hash;
  
  if ((jumpPoint != undefined) && ((jumpPoint == '#in-the-news') || (jumpPoint == '#endorsements'))) {  
      jQuery(jumpPoint).parent().addClass('selected');
      jQuery('html, body').animate(
          {
            scrollTop: parseInt(jQuery(jumpPoint).offset().top)
          },
          1000,
          function(){
          }
        );


  }
  
    
}

window.onload = function(){
  jQuery('.play-video')
    .click(function(e){
    
      var videoTrigger = jQuery(this);
      
      if(videoTrigger.is('.clicked')){
        ddVideoPlayer.playVideo();
        videoTrigger.css('display', 'none');
        // jQuery('.play-video').css('display', 'none');

      }else{
        if(videoTrigger.is('.default')){
          playVideo(videoTrigger.attr('href'));
          videoTrigger.addClass('clicked');
        }else{
          playVideo(videoTrigger.attr('href'));
          jQuery('.play-video').css('display', 'none');
        }
      }
      
      //hideShareVideoOverlay();
      
      e.preventDefault();
    });
  aboutList();
};
