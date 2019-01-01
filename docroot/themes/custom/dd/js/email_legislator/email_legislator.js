function setClipEmbed() {
  jQuery('.embed-link').click(function() {
    jQuery('.embed-clip-video').toggleClass('hide').children('textarea').select();
    jQuery(this).toggleClass('opened');
    return false;
  });
}


jQuery(document).ready(function(jQuery){
  jQuery.fn.setLegList = function(args) {
    console.log(args);
    jQuery(args).accordion();
    jQuery('.embed-link').removeClass('hide');
  };
  setClipEmbed();
});

