<?php

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_search_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  // Perform Hearing Inclusion / Exclusions.
  if (\Drupal\dd_base\DdBase::getSiteType() == \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
    $config = \Drupal::config(\Drupal\dd_admin\DdAdmin::getDdAdminContentSettingsName());
    $hearings_include_exclude = $config->get('hearings_include_exclude');
    $hids = $config->get('hearing_hids');

    if ($hids) {
      if ($view->id() == 'site_search') {
        $query->addCondition('hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
    }
  }
}
