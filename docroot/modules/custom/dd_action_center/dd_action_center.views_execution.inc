<?php

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_action_center_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if ($view->id() == 'campaign_teasers_action_center') {
    /** @var $query \Drupal\views\Plugin\views\query\Sql */
    // Hide private campaigns if not logged in.
    $user = \Drupal::currentUser();
    if (!$user->id()) {
      $query->addTable('node__field_private_campaign');
      $query->addWhere(1, 'node__field_private_campaign.field_private_campaign_value', 0);
    }
  }
}
