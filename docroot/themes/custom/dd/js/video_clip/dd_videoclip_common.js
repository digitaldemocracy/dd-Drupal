/*
 * common js code for pages with video clip
 */

function setClipPublishToggle() {
  jQuery('.share-video').click(function(evt) {
    var that = this;
    var clip_id = jQuery(this).data('clipid');
    jQuery.ajax({
      type: "POST",
      url: drupalSettings.path.baseUrl + "dd_video_editor/set_video_status/" + clip_id,
    })
    .success(function() {
      var span = jQuery(that).closest('.my-cp-video').find('.cp-video-size span.status');
      if (span.hasClass('private'))
        span.text('CLIP IS SHARED');
      else
        span.text('CLIP IS PRIVATE');
      span.toggleClass('private public');
      jQuery(that).closest('.my-cp-video').find('.social-lists').toggleClass('disable');
    });
    evt.preventDefault();
    return false;
  });
}

function setClipShare() {
  jQuery('.cp-video-share, .cd-video-share, .clip-social-bar').on('click', '.social-lists a:not(.link-email)', function() {
    if (jQuery(this).closest('ul.social-lists.disable').length > 0)
      alert('Clip must be published to share.');
    else if (jQuery(this).data('network')) {
      share(jQuery(this).data('network'), jQuery(this).data('url'));
    }
    return false;
  });

  jQuery('.cb-share-video').click(function(evt) {
    if (jQuery(this).parent().parent().parent().prev().find('ul.social-lists.disable').length > 0) {
      var that = this;
      var clip_id = jQuery(this).data('clipid');
      jQuery.ajax({
        type: "POST",
        url: drupalSettings.path.baseUrl + "dd_video_editor/set_video_status/" + clip_id,
      })
      .success(function() {
        var span = jQuery(that).parent().siblings('.cb-video-size').find('span.status');
        if (span.hasClass('private'))
          span.text('CLIP IS SHARED');
        else
          span.text('CLIP IS PRIVATE');
        span.toggleClass('private public');
        jQuery(that).parent().parent().parent().prev().find('.social-lists').toggleClass('disable');
        jQuery(that).parents('.clip-bank-videos').toggleClass('share-open');
        jQuery(that).parents('.clip-bank-videos').find('.cd-video-share').toggleClass('hide');
        jQuery(that).toggleClass('opened');
      });
    }
    else {
      jQuery(this).parents('.clip-bank-videos').toggleClass('share-open');
      jQuery(this).parents('.clip-bank-videos').find('.cd-video-share').toggleClass('hide');
      jQuery(this).toggleClass('opened');
    }
    evt.preventDefault();
    return false;
  });

  jQuery('.share-close').click(function(evt) {
    jQuery(this).parents('.clip-bank-videos').removeClass('share-open');
    jQuery(this).parents('.clip-bank-videos').find('.cd-video-share').addClass('hide');
    jQuery(this).parents('.clip-bank-videos').find('.cb-share-video').removeClass('opened');
  });
}

function setTranscriptEmbed() {
  jQuery('.embed-transcript').click(function() {
    if (jQuery(this).closest('ul.social-lists.disable').length > 0)
      return;
    jQuery('.embed-clip-transcript').toggleClass('hide').children('textarea').select();
    jQuery(this).toggleClass('opened');
    // Hide embed clip popup
    var nid = jQuery('.embed-clip').attr('data-nid');
    jQuery('#embed-video-' + nid).addClass('hide');
    jQuery('.embed-clip').removeClass('opened');
  });
}

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

function setupUserTags() {
  var parentDiv = jQuery('#vc-user-tags');
  var container = jQuery('#vc-user-tags-container');
  if (!(parentDiv.length && container.length))
    return;
  container.hide();
  
  // Set up toggling of tag visability
  parentDiv.find('a').click(function() {
    if (container.css('display') === 'none') {
      container.show();
      jQuery(this).text('Hide my tags');
    }
    else {
      container.hide();
      jQuery(this).text('See my tags');
    }
    // prevent event propagation
    return false;
  });

  // Set up tag list
  setupTagList();
  // Set up on click listener to append tag to video tag input
  container.on('click', '.user-tag', function() {
    var parentDiv = jQuery('#vc-user-tags');
    var input = parentDiv.prev().find('input[type=text]');
    var oldVal = input.val();
    if (oldVal.trim() === '')
      input.val(jQuery(this).text());
    else
      input.val(oldVal + ', ' + jQuery(this).text());
  });
}

function setupTagList() {
  // Set up tag list
  jQuery('body').find('#my-tags-list').remove();
  var container = jQuery('#vc-user-tags-container');
  var tags = jQuery('#user-tags-csv').data('user-tags').split(',');
  if (tags.length === 0 || tags[0].length === 0)
    jQuery(container).text('No Tags');
  else {
    jQuery(container).text('Click on tag to append to clip tags');
    tags.forEach(function(tag) {
      jQuery('<div id="my-tags-list"></div>').text(tag).addClass('user-tag').appendTo(container);
    });
  }
}

/**
 * function to set class 'active' to the active menu item
 */
/*function setActiveMenu() {
  var pathTokens = window.location.pathname.split('/');
  var currentPage = pathTokens[pathTokens.length - 1].replace('_','-');
  jQuery('.video-editor-menu-list a').each(function (i,o) {
    var $this = jQuery(o);
    $this.removeClass('active');
    if ($this.hasClass(currentPage)) $this.addClass('active');
  });
}*/

jQuery(document).ready(function(jQuery){
  //setActiveMenu();
  setupUserTags();
  setClipPublishToggle();
  setClipShare();
  setClipEmbed();
  setTranscriptEmbed();
  //set up videojs players
  var players = null;
  jQuery("video").each(function () {
    var id = jQuery(this).attr("id");
    if (id) {
      videojs(id, {}, function() {});
    }
    players = videojs.players;
  });
  if (!jQuery.isEmptyObject(players) && typeof onPlayersReady == 'function') {
    onPlayersReady(players);
  }
  
});
