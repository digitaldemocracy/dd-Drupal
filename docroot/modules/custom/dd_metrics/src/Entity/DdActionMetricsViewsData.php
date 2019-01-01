<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for DD Action Metrics entities.
 */
class DdActionMetricsViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_action_metrics']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('DD Action Metrics'),
      'help' => $this->t('The DD Action Metrics ID.'),
    );
    $data['dd_action_metrics']['campaign_action_id']['filter']['allow empty'] = TRUE;

    $data['node__field_campaign_action']['dd_action_metric'] = array(
      'title' => t('Action Metric'),
      'help' => t('Action Metric'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'dd_action_metrics',
        'base field' => 'campaign_action_id',
        'relationship field' => 'field_campaign_action_target_id',
        'id' => 'standard',
        'label' => 'Action Metric Link',
      ),
    );
    $data['dd_action_metrics']['dd_legislator'] = array(
      'title' => t('Action Metric DD Legislator'),
      'help' => t('Action Metric DD Legislator'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Legislator',
        'base field' => 'pid',
        'relationship field' => 'target_pid',
        'id' => 'standard',
        'label' => 'Action Metric DD Legislator Link',
      ),
    );
    $data['dd_action_metrics']['dd_action_stats'] = array(
      'title' => t('Campaign Action Stats'),
      'help' => t('Show campaign action stats'),
      'field' => array(
        'id' => 'dd_action_stats',
      ),
    );
    return $data;
  }

}
