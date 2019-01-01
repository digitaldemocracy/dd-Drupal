<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for DD Campaign Metrics entities.
 */
class DdCampaignMetricsViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
