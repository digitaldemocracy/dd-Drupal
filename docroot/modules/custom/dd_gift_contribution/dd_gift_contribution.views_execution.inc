<?php

/**
 * Implements hook_views_query_alter().
 */
function dd_gift_contribution_views_query_alter(\Drupal\views\ViewExecutable $view, \Drupal\views\Plugin\views\query\QueryPluginBase $query) {
  if ($view->id() == 'gifts' && $view->current_display == 'block_3') {
    // Office.
    if (isset($query->where[1]['conditions'][0]) && $query->where[1]['conditions'][0]['field'] == 'GiftCombined.legislatorPid') {
      $pid = $query->where[1]['conditions'][0]['value'];

      $query->where[1]['conditions'][] = array(
        'field' => 'GiftCombined.recipientPid',
        'value' => $pid,
        'operator' => '=',
      );

    }
  }
  elseif ($view->id() == 'gifts' && $view->current_display == 'block_4') {
    // Donors.
    if (isset($query->where[2]['conditions'][0]) && $query->where[2]['conditions'][0]['field'] == 'GiftCombined.legislatorPid') {
      $pid = $query->where[2]['conditions'][0]['value'];

      $query->where[2]['conditions'][] = array(
        'field' => 'GiftCombined.recipientPid',
        'value' => $pid,
        'operator' => '=',
      );

    }
  }

}
