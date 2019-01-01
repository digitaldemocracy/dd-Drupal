/* eslint-disable strict */

// Override autocomplete split values.
Drupal.autocomplete.splitValues = function (value) {
  return [value];
};

// Save the default autocomplete select handler.
Drupal.autocomplete.options.defaultSelectHandler = Drupal.autocomplete.options.select;

/**
 * Handles an autocompleteselect event.
 *
 * @param {jQuery.Event} event
 *   The event triggered.
 * @param {object} ui
 *   The jQuery UI settings object.
 *
 * @return {bool}
 *   Returns false to indicate the event status.
 */
Drupal.autocomplete.options.select = function (event, ui) {
  // If coming from main site search, redirect to URL.
  if (jQuery(this).parent().hasClass('form-item-dd-search-term')) {
    window.location = ui.item.value;
    return false;
  }
  else if (jQuery(this).parent().hasClass('form-item-field-speaker-pid-0-target-id')) {
    ui.item.value = ui.item.value.replace(/\[.*\]\s/,'');
  }
  else {
    Drupal.autocomplete.options.defaultSelectHandler(event, ui);
  }
};
