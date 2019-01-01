/**
 * JS for video annotator page
 */
(function (Popcorn) {
  var popcorn;
  var player;
  var targetFocus;
  var timelineItems;
  var timeline;
  var annoTarget = '#annotations';
  var videoTargetId = 'player1';

  /**
   * Init all elements on the page
   */
  jQuery(document).ready(function() {
    player = videojs(videoTargetId);
    var video = jQuery('#' + videoTargetId).find('video');
    popcorn = Popcorn('#' + video.attr('id'));
    timelineItems = new vis.DataSet({fieldId: '_id'});

    jQuery(annoTarget).css({ height: video.css('height'), width: video.css('width')});
    // Load annotations
    var anno = JSON.parse(jQuery('#annotations-json').text());
    anno.forEach(function(anno) {
        anno.target = annoTarget;
        anno.updateTargetFunc = updateTargetFocus;
        addAnnotation(anno);
      });

    // When the metadata about the video has been loaded, set up timeline
    player.on('loadedmetadata', function() {
      initTools();
      initTimeline();
    });
  });

  /**
   * Inits edit tools
   */
  function initTools() {
    // Add New Annotation button
    jQuery('#addAnnoBtn').click(function() {
      player.pause();
      var start = Math.round(player.currentTime() * 10) / 10;
      var end = start + 5;
      player.currentTime(start);

      trackId = addAnnotation({
        left: 200,
        top: 200,
        height: 100,
        width: 100,
        start: start,
        end: end,
        content: 'NEW ANNOTATION',
        color: 'blue',
        font: 'Courier New, Courier New, Courier, monospace',
        fontSize: '14px',
        fontColor: 'white',
        linked: false,
        target: annoTarget,
        updateTargetFunc: updateTargetFocus
      });

      updateTargetFocus(popcorn.getTrackEvent(trackId));
    });

    // Save Changes button
    jQuery('#edit-submit').click(function() {
      // push a JSON describing each annotation to #field-anno
      var tracks = [];
      popcorn.getTrackEvents().forEach(function(track) {
        tracks.push({
          start: track.start,
          end: track.end,
          content: track.content,
          left: track.left,
          top: track.top,
          width: track.width,
          height: track.height,
          color: track.color,
          font: track.font,
          fontColor: track.fontColor,
          fontSize: track.fontSize,
          linked: track.linked,
          link: track.link
        });
      });

      jQuery('#edit-anno').val(JSON.stringify(tracks));
    });
    
    // Track Start time field
    function updateStart() {
      var start = jQuery(this).val();
      if (start !== '' && !start.endsWith('.')) {
        start = Number(start);
        update({
          _id: targetFocus._id, 
          start: start, 
          // make sure start comes before end
          end: start > targetFocus.end - .1 ? start + .1 : targetFocus.end
        });
      }
    }
    jQuery('#trackStart').spinner({
      min: 0, 
      max: player.duration(), 
      step: 0.1, 
      stop: updateStart, 
      spin: updateStart, 
      change: updateStart
    });
    
    // Track End time field
    function updateEnd() {
      var end = jQuery(this).val();
      if (end !== '' && !end.endsWith('.')) {
        end = Number(end);
        update({
          _id: targetFocus._id, 
          end: end,
          // make sure start comes before end
          start: targetFocus.start > end - .1 ? end - .1 : targetFocus.start
        });
      }
    }
    jQuery('#trackEnd').spinner({
      min: 0, 
      max: player.duration(), 
      step: 0.1, 
      stop: updateEnd, 
      spin: updateEnd, 
      change: updateEnd
    });
    
    // Track text field
    jQuery('#trackContent').on('input', function() {
      // Clean tex of any html tags
      var d = document.createElement('div');
      d.innerHTML = jQuery(this).val();
      var val = d.textContent || d.innerText || '';

      update({_id: targetFocus._id, content: val});
    });

    // Track backgound color
    jQuery('#trackColor').spectrum({
      change: function(color) {
        update({_id: targetFocus._id, color: color.toHexString()});
      }
    });

    // Track Link enable button
    jQuery('#trackEnableLink').change(function() {
      var checked = jQuery(this).prop('checked');
      jQuery('#trackLink').parent().toggle(checked);
      update({_id: targetFocus._id, linked: checked});
    });

    // Track link field
    jQuery('#trackLink').change(function() {
      update({_id: targetFocus._id, link: jQuery(this).val()});
    });

    // Delete track button
    jQuery('#trackDeleteBtn').click(function() {
      timelineItems.remove(targetFocus._id);
      popcorn.removeTrackEvent(targetFocus._id);
      targetFocus._container.remove();
      jQuery('.trackEdit').hide().prop('disabled', true);
    });

    // Font selector
    jQuery('#trackFont').fontSelector({
      'hide_fallbacks': true,
      selected: function(style) { 
        update({_id: targetFocus._id, font: style});
      },
      fonts: [
        'Arial,Arial,Helvetica,sans-serif',
        'Arial Black,Arial Black,Gadget,sans-serif',
        'Comic Sans MS,Comic Sans MS,cursive',
        'Courier New,Courier New,Courier,monospace',
        'Georgia,Georgia,serif',
        'Impact,Charcoal,sans-serif',
        'Lucida Console,Monaco,monospace',
        'Lucida Sans Unicode,Lucida Grande,sans-serif',
        'Palatino Linotype,Book Antiqua,Palatino,serif',
        'Tahoma,Geneva,sans-serif',
        'Times New Roman,Times,serif',
        'Trebuchet MS,Helvetica,sans-serif',
        'Verdana,Geneva,sans-serif',
        'Gill Sans,Geneva,sans-serif'
      ]
    });

    // Track Font Size
    function updateSize() {
      var size = jQuery(this).val();
      if (!isNaN(size)) {
        update({_id: targetFocus._id, fontSize: size + 'px'});
      }
    }
    jQuery('#trackFontSize').spinner({
      min: 10, 
      max: 100, 
      stop: updateSize, 
      spin: updateSize,
      change: updateSize
    });

    // Track Font color
    jQuery('#trackFontColor').spectrum({
      change: function(color) {
        update({_id: targetFocus._id, fontColor: color.toHexString()});
      }
    });

    jQuery('.trackEdit').hide().prop('disabled', true);
  }

  /**
   * Inits timeline module of vis.js
   */
  function initTimeline() {
    // When timeline is updated, update corespoding popcorn track
    timelineItems.on('update', function(e, prop) {
      // If prop.data changes values in oldData
      var same = true;
      for (key in prop.data[0]) {
        same &= prop.data[0][key].valueOf() === prop.oldData[0][key].valueOf();
      }
      if (!same) {
        update(prop.data[0]);
      }
    });

    // When timeline item is deleted, delete coresponding popcorn track
    timelineItems.on('remove', function(e, prop) {
      var id = prop.items[0];
      popcorn.getTrackEvent(id)._container.remove();
      popcorn.removeTrackEvent(id);

      jQuery('.trackEdit').hide().prop('disabled', true);
    });

    // timeline options
    var options = {
      editable: {
        remove: true,
        updateTime: true
      },
      showCurrentTime: false,
      start: secToDate(0),
      end: secToDate(10),
      min: secToDate(0),
      max: secToDate(player.duration()),
      width: player.width(),
      minHeight: '200px',
      showMajorLabels: false,
      maxMinorChars: 12,
      format: {
        minorLabels: {
          millisecond:'HH:mm:ss:SSS',
          second:     'HH:mm:ss',
          minute:     'HH:mm:ss',
          hour:       'HH:mm:ss'
        }
      },
      snap: function(date, scale, step) {
        // Snap to 1/10s
        date.setMilliseconds(Math.round(date.getMilliseconds() / 100) * 100);
        return date;
      },
      zoomMax: 120000,
      zoomMin: 2000
    };

    // Create Timeline
    timeline = new vis.Timeline(document.getElementById('timeline'), 
                                timelineItems, options);
    
    // Add CustomTime bar
    timeline.addCustomTime(secToDate(0));

    // Sets focus when timeline item is selected
    timeline.on('select', function(prop) {
      jQuery('#' + videoTargetId).addClass('vjs-has-started');       
      if (prop.items.length) {
        updateTargetFocus(popcorn.getTrackEvent(prop.items[0]));

        // If videoTime is outside of annotation start and stop,
        // move to start of annotation
        var videoTime = dateToSec(timeline.getCustomTime());
        if (videoTime < targetFocus.start || videoTime > targetFocus.end) {
          player.pause();
          player.currentTime(targetFocus.start);
          timeline.setCustomTime(secToDate(targetFocus.start));
        }
      }
      // Don't allow unselecting
      else
        timeline.setSelection(targetFocus._id);
    });

    // When customtime bar is moved, update player to coresponding time
    timeline.on('timechange', function(e) {
      player.currentTime(dateToSec(e.time));
    });

    // When player time changes, update custom time bar to coresponding time
    player.on('timeupdate', function(e) {
      var that = this;
      var time = secToDate(this.currentTime());
      timeline.setCustomTime(time);
      timeline.moveTo(time, {animation: false});
      window.setTimeout(function() {
        var time = secToDate(that.currentTime());
        timeline.setCustomTime(time);
        timeline.moveTo(time, {animation: false});
      }, 100);
    });
  }

  /**
   * Creates a new annotation.
   * Creates both popcorn track and timeline object
   * 
   * Returns new annotations id
   */
  function addAnnotation(options) {
    // Create new popcorn track
    popcorn.annotation(options);
    var trackId = popcorn.getLastTrackEventId();
    // Create timeline object
    timelineItems.add({
      _id: trackId,
      content: options.content,
      start: secToDate(options.start),
      end: secToDate(options.end)
    });
    
    return trackId;
  }

  /**
   * Helper function to convert from date object to second duration
   */
  function dateToSec(d) {
    var baseTime = '2000-01-01 00:00:00';
    return ((new Date(d).getTime()) - (new Date(baseTime).getTime())) / 1000;
  }

  /**
   * Helper function to convert from second duration to date object
   */
  function secToDate(s) {
    var baseTime = '2000-01-01 00:00:00';
    return new Date(new Date(baseTime).getTime() + s * 1000);
  }

  /**
   * Updates the annotation that is currently focused on
   */
  function updateTargetFocus(target) {
    if (target !== targetFocus) {
      jQuery('.trackEdit').show().prop('disabled', false);
      jQuery('.target-focus').removeClass('target-focus');
      target._container.addClass('target-focus');
      targetFocus = target;
  
      // Set track edit fields    
      updateEditFields(target._id);
      // Mirror selection onto timeline
      timeline.setSelection(target._id);
    }
    else {
      target._container.addClass('target-focus');
    }
  }

  /**
   * Updates annotation. Requires [_id] field
   * Updates both popcorn item and timeline item
   */
  function update(fields) {
    // Update items
    popcorn.annotation(fields._id, popFields(fields));
    timelineItems.update(timelineFields(fields))
    // Reflect changes to UI
    updateEditFields(fields._id);
    updateTargetFocus(popcorn.getTrackEvent(fields._id));
  }

  /**
   * Constructs object with required fields and data types for timeline
   */
  function timelineFields(fields) {
    var newFields = {
      _id: fields._id,
    };

    if ('content' in fields)
      newFields.content = fields.content;
    if ('start' in fields)
      newFields.start = secToDate(boundTime(fields.start));
    if ('end' in fields)
      newFields.end = secToDate(boundTime(fields.end));

    return newFields;
  }

  /**
   * Constructs object with required fields and data types for popcorn
   */
  function popFields(fields) {
    var newFields = {};
    
    if ('content' in fields)
      newFields.content = fields.content;
    if ('start' in fields)
      newFields.start = boundTime(fields.start);
    if ('end' in fields)
      newFields.end = boundTime(fields.end);
    if ('width' in fields)
      newFields.width = fields.width;
    if ('height' in fields)
      newFields.height = fields.height;
    if ('top' in fields)
      newFields.top = fields.top;
    if ('left' in fields)
      newFields.left = fields.left;
    if ('target' in fields)
      newFields.target = fields.target;
    if ('color' in fields)
      newFields.color = fields.color;
    if ('font' in fields)
      newFields.font = fields.font;
    if ('fontSize' in fields)
      newFields.fontSize = fields.fontSize;
    if ('fontColor' in fields)
      newFields.fontColor = fields.fontColor;
    if ('linked' in fields)
      newFields.linked = fields.linked;
    if ('link' in fields)
      newFields.link = fields.link;

    return newFields;
  }

  /**
   * Bounds start and end values to start and end of video. 
   * Returns bounded time in seconds
   */
  function boundTime(time) {
    if (time instanceof Date)
      time = dateToSec(time);
    return Math.min(Math.max(0, time), player.duration());
  }

  /**
   * Updates Edit fields with the correct values based on track with [id]
   */
  function updateEditFields(id) {
    var fields = popcorn.getTrackEvent(id);

    jQuery('#trackStart').val(fields.start);
    jQuery('#trackEnd').val(fields.end);
    jQuery('#trackLink').val(fields.link);
    jQuery('#trackContent').val(fields.content);
    jQuery('#trackColor').spectrum('set', fields.color);
    jQuery('#trackFont').fontSelector('select', fields.font);
    jQuery('#trackFontSize').val(fields.fontSize.replace('px', ''));
    jQuery('#trackFontColor').spectrum('set', fields.fontColor);
    jQuery('#trackEnableLink').prop('checked', fields.linked);
    jQuery('#trackLink').parent().toggle(fields.linked);
  }

})(Popcorn);
