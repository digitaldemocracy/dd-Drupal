function setClipEmbed() {
  jQuery('.embed-clip').click(function() {
    if (jQuery(this).closest('ul.social-lists.disable').length > 0)
      return;
    var nid = jQuery(this).attr('data-nid');
    jQuery('#embed-video-' + nid).toggleClass('hide').children('textarea').select();
    jQuery(this).toggleClass('opened');
    // Hide embed transcript popup
    jQuery('.embed-clip-transcript').addClass('hide');
    jQuery('.embed-transcript').removeClass('opened');
  });

  jQuery('.my-clip-list .embed-clip').click(function() {
    if (jQuery(this).closest('ul.social-lists.disable').length > 0)
      return;
    jQuery(this).parents('.clip-bank-videos').find('.share-close').addClass('hide');
    jQuery(this).parents('.clip-bank-videos').find('.embed-close').show();
  });

  jQuery('.page-my-clip-bank .embed-clip').click(function() {
    if (jQuery(this).closest('ul.social-lists.disable').length > 0)
      return;
    jQuery(this).parents('.col4').find('.my-cp-video').addClass('opned-embed');
  });
  jQuery('.page-my-clip-bank .embed-close').click(function() {
    jQuery(this).parents('.col4').find('.my-cp-video').removeClass('opned-embed');
    jQuery(this).parents('.col4').find('.embed-clip').removeClass('opened');
    jQuery(this).parents('.col4').find('.embed-clip-video').addClass('hide');
  });
  jQuery('.my-clip-list .embed-close').click(function() {
    jQuery(this).parents('.clip-bank-videos').find('.embed-clip-video').addClass('hide');
    jQuery(this).parents('.clip-bank-videos').find('.share-close').removeClass('hide');
    jQuery(this).parents('.clip-bank-videos').find('.embed-clip').removeClass('opened');
    jQuery(this).hide();
  });
}

function initFBShareLinks(wrapper){
        if(wrapper == null)
                var wrapper = jQuery('body');

        wrapper.find('a.fb-share, a.js-fb-share, a.js-lnk-fb-share')
                .click(function(){

						var shareLink = jQuery(this);
						window.open(shareLink.attr('href'),'FacebookShare','toolbar=0,status=0,width=626,height=436');
                        return false;
                });
}
function initTWShareLinks(wrapper){
        if(wrapper == null)
                var wrapper = jQuery('body');

        wrapper.find('a.tw-share, a.js-tw-share, a.js-lnk-tw-share')
                .click(function(){
                                        var shareLink = jQuery(this);
                                        window.open(shareLink.attr('href'),'TwitterShare','toolbar=0,status=0,width=626,height=436');
                                        return false;
                                });
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

  initTWShareLinks();
  initFBShareLinks();
  setClipEmbed();
});

