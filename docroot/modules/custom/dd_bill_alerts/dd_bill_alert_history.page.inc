<?php

/**
 * @file
 * Contains dd_bill_alert_history.page.inc.
 *
 * Page callback for Dd bill alert history entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Dd bill alert history templates.
 *
 * Default template: dd_bill_alert_history.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_bill_alert_history(array &$variables) {
  // Fetch DdBillAlertHistory Entity Object.
  $dd_bill_alert_history = $variables['elements']['#dd_bill_alert_history'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
