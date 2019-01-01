<?php

/**
 * @file
 * Contains dd_lobbyist.page.inc.
 *
 * Page callback for DD Lobbyist entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Lobbyist templates.
 *
 * Default template: dd_lobbyist.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_lobbyist(array &$variables) {
  // Fetch DdLobbyist Entity Object.
  $dd_lobbyist = $variables['elements']['#dd_lobbyist'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
