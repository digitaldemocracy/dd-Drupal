<?php

/**
 * Implements hook_views_query_alter().
 */
function dd_utterance_views_query_alter(\Drupal\views\ViewExecutable $view, \Drupal\views\Plugin\views\query\QueryPluginBase $query) {
  if ($view->id() == 'video_speakers') {
    // Allow a start/time end contextual argument to pass to filter for Utterance.
    foreach ($query->where as &$where_value) {
      if (isset($where_value['conditions'])) {
        foreach ($where_value['conditions'] as $condition_index => &$condition) {
          if ($condition['field'] == 'Utterance.time' && $condition['operator'] == '>=') {
            if (isset($view->args[1])) {
              $condition['value'] = $view->args[1];
            }
          }
          else if ($condition['field'] == 'Utterance.time' && $condition['operator'] == '<=') {
            if (isset($view->args[2])) {
              $condition['value'] = $view->args[2];
            }
            else {
              unset($where_value['conditions'][$condition_index]);
            }
          }
        }
      }
    }
  }
}
