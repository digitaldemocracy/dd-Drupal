<?php
/**
 * @file
 * Provides views data and hooks for dd_base module.
 */

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\dd_base\DdBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_base_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if (isset($query->tables)) {
    foreach ($query->tables as $table_name => $table) {
      if (\Drupal\dd_base\Entity\DdBaseStateField::doesTableUseStateField($table_name)) {
        $query->addWhere(array(), $table_name . '.state', DdBase::getCurrentState());
      }
    }
  }
}
