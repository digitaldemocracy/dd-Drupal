<?php

/**
 * @file
 * Contains dd_contribution.page.inc.
 *
 * Page callback for DD Contribution entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Contribution templates.
 *
 * Default template: dd_contribution.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_contribution(array &$variables) {
  // Fetch DdContribution Entity Object.
  $dd_contribution = $variables['elements']['#dd_contribution'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
