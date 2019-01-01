<?php

/**
 * @file
 * Contains dd_bill.page.inc.
 *
 * Page callback for DD Bill entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Bill templates.
 *
 * Default template: dd_bill.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_bill(array &$variables) {
  // Fetch DdBill Entity Object.
  $dd_bill = $variables['elements']['#dd_bill'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
