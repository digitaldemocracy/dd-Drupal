/* eslint-disable strict */
/**
 * Override ajax_view.js core views JS to allow Ajax views in views_field_view
 * @return {bool}
 *   Returns TRUE.
 *
 */
Drupal.views.ajaxView.prototype.filterNestedViews = function () {
  // Output a console message so this can be found later.
  console.log('dd_common.js Override ajax_view.js filterNestedViews allowing nested ajax views');
  return true;
};
