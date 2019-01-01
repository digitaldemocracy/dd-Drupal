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

function updateVideoOrder() {
  var vids = [];
  jQuery.each(jQuery('#drop-placeholder .bank-clip'), function(i,o){
    vids.push(jQuery(o).data('clip-id'));});
  console.log(vids);
  jQuery('input[name=videos]').val(vids.join());	

  if (vids.length == 0) {
    jQuery('#drop-placeholder').addClass( "empty-drop" );
    jQuery('#drop-placeholder').parent().removeClass( "clip-added" );
  }
}

jQuery.fn.playVideo = function(args) {
  jQuery('#create_form').css('display', 'block');
  jQuery('input[name=videos_rendered]').val(jQuery('input[name=videos]').val()); 
  jQuery('input[name=duration]').val(args.duration);
  jQuery('input[name=size]').val(args.size);
  jQuery('input[name=videoid]').val(args.uri.split('videos/')[1].split(".")[0])
  jQuery('.video-wrapper').removeClass('hide');

  window.player1 = new DDVideoClipping(window.player1.player);
  //window.player1.player.src([{type:"video/mp4", src:args.uri, codecs:"avc1.4D401E, mp4a.40.2"}]);
  window.player1.player.src([{type:"video/mp4", src:args.uri}]);
  window.player1.playVideo();
};

jQuery.fn.reloadPage = function(args) {
  window.location.reload();
}

var sortableIn;
var firstLoad = true;

(function (jQuery) {
  Drupal.behaviors.videoBuilder = {
    attach: function (context, settings) {

      
      if(firstLoad) {
          firstLoad = false;

        jQuery("#drop-placeholder").sortable();
        jQuery("#drop-placeholder").disableSelection();

        jQuery(".qitem").draggable({
          containment : "#container",
          helper : 'clone',
          revert : 'invalid',
          handle : '.drag-handler'
        });

        jQuery("#drop-placeholder").sortable({
          connectWith: ".droppable",
          over: function(e, ui) { sortableIn = 1; },
          out: function(e, ui) { sortableIn = 0; },
          beforeStop: function (event, ui) {
             if (sortableIn == 0) { 
                ui.item.remove(); 
                updateVideoOrder();     
             }
          },
          update: function(event, ui) {
            updateVideoOrder();     
          }
        }).disableSelection();
        
        jQuery("#drop-placeholder").droppable({
          hoverClass : 'ui-state-highlight',
          accept: ":not(.ui-sortable-helper)",
          drop : function(ev, ui) {
            jQuery(ui.draggable).clone().appendTo(this);
            jQuery( this ).removeClass( "empty-drop" );
            jQuery( this ).parent().addClass( "clip-added" );
            jQuery('.cp-video-close').click(function() {
              jQuery(this).parent().remove();
              updateVideoOrder();
            });
            updateVideoOrder();
          }
        });


      }

      
    }
  };
}(jQuery));

jQuery(document).ready(function(jQuery){
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
