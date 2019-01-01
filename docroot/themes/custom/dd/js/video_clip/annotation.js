/**
 * Popcorn.js plugin, handles annotations.
 */
Popcorn.plugin( "annotation" , function() {
    
  /**
   * Creates Annotation DOM object
   */
  function constAnnotation(track, popcorn) {
    // Main body of annotation
    var anno = jQuery('<div></div>').addClass('annotation').css({
      top: track.top,
      left: track.left,
      width: track.width,
      height: track.height,
      'background-color': track.color,
      'font-family': track.font,
      'font-size': track.fontSize,
      'color': track.fontColor
    }).attr({id: track._id}).hide().appendTo(jQuery(track.target));

    // Annotation content
    jQuery('<div></div>').addClass('annotation-content')
      .text(track.content).appendTo(anno);

    // Annotation link
    if (track.linked) {
      jQuery('<div></div>').addClass('annotation-link').appendTo(anno);
      anno.addClass('has-link').click(function() { 
        if (anno.parent().hasClass('view-mode'))
          window.open(track.link); 
      });
    }

    // Check to see if annotations should be Draggable and Resizable
    if (anno.parent().hasClass('view-mode')) {
      // Annotation close button
      var close = jQuery('<div></div>').addClass('annotation-close').click(function() {
        popcorn.removeTrackEvent(track._id);
        anno.remove();
      }).appendTo(anno);
    }
    else {
      anno.draggable({
        containment: 'parent',
        cursor: 'move',
        start: function() {
          track.updateTargetFunc(popcorn.getTrackEvent(track._id));
        },
        stop: function(event, ui) {
          popcorn.annotation(track._id, {
            top: ui.position.top,
            left: ui.position.left
          });
          track.updateTargetFunc(popcorn.getTrackEvent(track._id));
        }
      }).resizable({
        containment: 'parent',
        handles: 'all',
        autoHide: true,
        start: function() {
          track.updateTargetFunc(popcorn.getTrackEvent(track._id));
        },
        stop: function(event, ui) {
          popcorn.annotation(track._id, {
            top: ui.position.top,
            left: ui.position.left,
            width: ui.size.width,
            height: ui.size.height
          });
          track.updateTargetFunc(popcorn.getTrackEvent(track._id));
        }
      }).click(function() {
        track.updateTargetFunc(popcorn.getTrackEvent(track._id));
      }).css({ position: 'absolute' });
    }

    return anno;
  }

  return {
    // Called whenever popcorn.annotation is called
    _setup : function(track) {
      track._container = constAnnotation(track, this);
    },
    // Removes old annotation container
    _teardown: function (track) {
      track._container.remove();
    },
    // Called when start timeEvent occurs
    start: function(event, track) {
      track._container.show();
    },
    // Called when end timeEvent occurs
    end: function(event, track) {
      track._container.hide();
    }
  }
});
