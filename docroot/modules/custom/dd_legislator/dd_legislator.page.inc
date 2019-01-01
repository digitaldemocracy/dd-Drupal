<?php

/**
 * @file
 * Contains dd_legislator.page.inc.
 *
 * Page callback for DD Legislator entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Legislator templates.
 *
 * Default template: dd_legislator.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_legislator(array &$variables) {
  // Fetch DdLegislator Entity Object.
  $dd_legislator = $variables['elements']['#dd_legislator'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
