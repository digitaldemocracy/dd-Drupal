<?php

/**
 * @file
 * Contains dd_authors.page.inc.
 *
 * Page callback for DD Authors entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Authors templates.
 *
 * Default template: dd_authors.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_authors(array &$variables) {
  // Fetch DdAuthors Entity Object.
  $dd_authors = $variables['elements']['#dd_authors'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
