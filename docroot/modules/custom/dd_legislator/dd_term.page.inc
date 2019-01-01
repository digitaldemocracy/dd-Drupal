<?php

/**
 * @file
 * Contains dd_term.page.inc.
 *
 * Page callback for DD Term entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Term templates.
 *
 * Default template: dd_term.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_term(array &$variables) {
  // Fetch DdTerm Entity Object.
  $dd_term = $variables['elements']['#dd_term'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
