(function (Drupal, $) {
  $('.playbutton,img').click(function() {
    var video = '<div class="video-container"><iframe src="https://www.youtube.com/embed/PDCfDJL0VSY?&amp;autoplay=1&amp;rel=0&amp;fs=0&amp;showinfo=0&amp;autohide=3&amp;modestbranding=1"></iframe></div>';
    $('.video').hide();
    $('.tube').html(video);
    $('.close').show();
  });

  $('.close').click(function(){
    $('.video').show();
    $('.tube').empty();
    $('.close').hide();
  });

})(Drupal, jQuery);
