<?php

/**
 * @file
 * Contains dd_bill_version.page.inc.
 *
 * Page callback for DD Bill Version entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Bill Version templates.
 *
 * Default template: dd_bill_version.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_bill_version(array &$variables) {
  // Fetch DdBillVersion Entity Object.
  $dd_bill_version = $variables['elements']['#dd_bill_version'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
