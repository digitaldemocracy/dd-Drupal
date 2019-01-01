<?php

namespace Drupal\metatag_views\Form;

use Drupal\views\ViewEntityInterface;


trait MetatagViewsOptionsSubmitTrait {
  // Default route to redirect after the form submission.
  protected $redirectRoute = 'metatag_views.metatags.list';

  /**
   * Sets the metatag display extender options. Unsets if NULL
   *
   * @param ViewEntityInterface $view
   *  View object to act upon.
   * @param $display_id
   *  Display id to set / reset.
   * @param array|null $values
   *  Values to set. Unsets the values if NULL given.
   */
  public function setMetatagDisplayExtenderValues(ViewEntityInterface $view, $display_id, $values = NULL) {
    /** @var array $displays An array of displays */
    $displays = $view->get('display');
    // Set proper values for the display extender.
    $element = is_null($values) ? [] : [ 'metatags' => $values ];

    $element['metatags'] = array_merge($displays[$display_id]['display_options']['display_extenders']['metatag_display_extender']['metatags'], $element['metatags']);

    $displays[$display_id]['display_options']['display_extenders']['metatag_display_extender'] = $element;

    // Set the displays back to the view
    $view->set('display', $displays);
    // Save the views.
    $view->save();
  }
}