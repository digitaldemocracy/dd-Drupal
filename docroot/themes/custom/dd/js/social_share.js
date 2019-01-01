/**
 * This file contains functions for creating social share popup.
 * This file should be included in any page with social share button.
 */

var SharePopups = {};

function openSharePopup(params){
  var winParams =  jQuery.extend(
    {
      toolbar: 0,
      status: 0,
      width: 600,
      height: 450
    },
    params['windowParams']
  );

  winParams['left'] = (screen.width - winParams['width'])/2;
  winParams['top'] = (screen.height - winParams['height'])/2;

  var serializedWinParams = jQuery.param(winParams);
  serializedWinParams = serializedWinParams.replace(/\&/g, ',');

  if(SharePopups[params['windowName']]){
    SharePopups[params['windowName']].close();
  }
  setTimeout(function(){
    SharePopups[params['windowName']] = window.open(params['windowURL'], params['windowName'], serializedWinParams);
  }, 10);
}

function share(shareType,url){
  var shareURL = url;
  var shareTitle = 'Watch this video shared from Digital Democracy.';
  var shareDescription = 'Check out this clip!';
  var shareTweet = 'Watch this #CAlegislature video clip via @DDemocracyCA';
  var urlParts = url.split("/");
  var clip_id = urlParts ? urlParts[urlParts.length - 1] : null;
  var videoURI = clip_id ? "https://videostorage-us-west.s3.amazonaws.com/videos/"
                             + clip_id + "/" + clip_id + ".mp4" : "";
  var sharePhoto = clip_id ? "https://videostorage-us-west.s3.amazonaws.com/videos/"
                             + clip_id + "/thumbnails/default.jpg" : "";

  switch(shareType){
    case 'facebook':
      shareURL = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareURL);
      openSharePopup({
        windowURL: shareURL,
        windowImage: sharePhoto,
        windowName: 'FacebookVideoShare'
      });
      break;
  case 'twitter':
    shareURL = 'https://twitter.com/intent/tweet?url='
             + encodeURIComponent(shareURL) + '&text='
             + encodeURIComponent(shareTweet);
    openSharePopup({
      windowURL: shareURL,
      windowImage: sharePhoto,
      windowName: 'TwitterLinkShare'
    });
    break;
  case 'linkedin':
    shareURL = 'https://www.linkedin.com/shareArticle?mini=true&url='
             + encodeURIComponent(shareURL) + '&title=' + encodeURIComponent(shareTitle)
             + '&summary=' + encodeURIComponent(shareDescription) + '&source=';
    openSharePopup({
      windowURL: shareURL,
      windowImage: sharePhoto,
      windowName: 'LinkedinLinkShare'
    });
    break;
  case 'reddit':
    shareURL = '';
    openSharePopup({
      windowURL: shareURL,
      windowImage: sharePhoto,
      windowName: 'RedditLinkShare'
    });
    break;
  case 'google-plus':
    shareURL = 'https://plus.google.com/share?url=' + encodeURIComponent(shareURL);
    openSharePopup({
      windowURL: shareURL,
      windowImage: sharePhoto,
      windowName: 'GooglePlusLinkShare'
    });
    break;
  }
  return false;
}


