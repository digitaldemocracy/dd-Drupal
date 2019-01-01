<?php

/**
 * @file
 * Contains dd_committee_authors.page.inc.
 *
 * Page callback for DD Committee Authors entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for DD Committee Authors templates.
 *
 * Default template: dd_committee_authors.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_committee_authors(array &$variables) {
  // Fetch DdCommitteeAuthors Entity Object.
  $dd_committee_authors = $variables['elements']['#dd_committee_authors'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
