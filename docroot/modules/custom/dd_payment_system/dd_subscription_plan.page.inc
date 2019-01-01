<?php

/**
 * @file
 * Contains dd_subscription_plan.page.inc.
 *
 * Page callback for DD Subscription Plan entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for DD Subscription Plan templates.
 *
 * Default template: dd_subscription_plan.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_subscription_plan(array &$variables) {
  // Fetch DdSubscriptionPlan Entity Object.
  $dd_subscription_plan = $variables['elements']['#dd_subscription_plan'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
