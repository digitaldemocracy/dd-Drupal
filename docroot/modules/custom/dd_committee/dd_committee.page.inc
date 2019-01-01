<?php

/**
 * @file
 * Contains dd_committee.page.inc.
 *
 * Page callback for DD Committee entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Committee templates.
 *
 * Default template: dd_committee.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_committee(array &$variables) {
  // Fetch DdCommittee Entity Object.
  $dd_committee = $variables['elements']['#dd_committee'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
