<?php

/**
 * @file
 * Contains dd_serves_on.page.inc.
 *
 * Page callback for DD ServesOn entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD ServesOn templates.
 *
 * Default template: dd_serves_on.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_serves_on(array &$variables) {
  // Fetch DdServesOn Entity Object.
  $dd_serves_on = $variables['elements']['#dd_serves_on'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
