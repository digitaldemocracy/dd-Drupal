/**
 *  * function to set class 'active' to the active menu item
 *   */
function setActiveMenu() {
  var pathTokens = window.location.pathname.split('/');
  var currentPage = pathTokens[pathTokens.length - 1].replace(/_/g,'-').trim();
  console.log(currentPage);
  jQuery('.video-editor-menu-list a').each(function (i,o) {
    var $this = jQuery(o);
    $this.removeClass('active');
    if ($this.hasClass(currentPage)) $this.addClass('active');
  });
}

jQuery(document).ready(function(jQuery){
  setActiveMenu();
});
