<?php

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_committee_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if ($view->id() == 'committee_members_per_year' && $view->current_display == 'default') {
    /** @var $query \Drupal\views\Plugin\views\query\Sql */
    $query->addWhereExpression(1, 'Term_servesOn.year = servesOn.year');
  }

  // Perform Hearing Inclusion / Exclusions.
  if (
    \Drupal\dd_base\DdBase::getSiteType() == \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL &&
    $view->id() == 'committees_faceted'
  ) {
    $config = Drupal::config(\Drupal\dd_admin\DdAdmin::getDdAdminContentSettingsName());
    $committees_include_exclude = $config->get('committees_include_exclude');
    $cn_ids = $config->get('committee_cn_ids');

    if ($cn_ids) {
      $query->addCondition('cn_id', $cn_ids, $committees_include_exclude ? 'IN' : 'NOT IN');
    }
  }
}
