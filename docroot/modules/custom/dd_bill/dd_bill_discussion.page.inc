<?php

/**
 * @file
 * Contains dd_bill_discussion.page.inc.
 *
 * Page callback for DD Bill Discussion entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Bill Discussion templates.
 *
 * Default template: dd_bill_discussion.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_bill_discussion(array &$variables) {
  // Fetch DdBillDiscussion Entity Object.
  $dd_bill_discussion = $variables['elements']['#dd_bill_discussion'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
