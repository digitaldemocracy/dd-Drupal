<?php

/**
 * @file
 * Contains dd_utterance.page.inc.
 *
 * Page callback for DD Utterance entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Utterance templates.
 *
 * Default template: dd_utterance.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_utterance(array &$variables) {
  // Fetch DdUtterance Entity Object.
  $dd_utterance = $variables['elements']['#dd_utterance'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
