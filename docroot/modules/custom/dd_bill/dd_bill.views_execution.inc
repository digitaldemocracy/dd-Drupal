<?php

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_bill_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if ($view->id() == 'hearing_participation_bills' && $view->current_display == 'hearing_bill_json') {
    /** @var $query \Drupal\views\Plugin\views\query\Sql */
    // For joining LegParticipation on bid and hid.
    $query->addWhereExpression(1, 'LegParticipation_BillDiscussion.hid = BillDiscussion.hid');
  }
}

/**
 * Implements hook_views_post_render().
 */
function dd_bill_views_post_render(ViewExecutable $view, &$output, \Drupal\views\Plugin\views\cache\CachePluginBase $cache) {
  if ($view->id() == 'bill_vote_details' && $view->current_display == 'default') {
    $ordered_rows = [];
    $last_row = [];

    // Reorder rows so that ABS votes is last.
    foreach ($output['#rows'] as $row) {
      if ($row['#title'] == 'ABS Votes') {
        $last_row[] = $row;
      }
      else {
        $ordered_rows[] = $row;
      }
    }
    $output['#rows'] = array_merge($ordered_rows, $last_row);
  }
}
