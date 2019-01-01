(function (Popcorn) {
  /**
   * Init all elements on the page
   */
  jQuery(document).ready(function() {
    // Load annotations
    var video = jQuery(document).find('video');
    var popcorn = Popcorn('#' + video.attr('id'));
    console.log(popcorn);
    var annoTarget = '#annotations';
    jQuery(annoTarget).css({ height: video.css('height'), width: video.css('width')});
    var anno = JSON.parse(jQuery('#annotations-json').text());
    anno.forEach(function(anno) {
        anno.target = annoTarget;
        popcorn.annotation(anno);
      });

  });

})(Popcorn);
