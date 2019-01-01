<?php

namespace Drupal\dd_metrics;

use Drupal\dd_metrics\Entity\DdActionMetrics;
use Drupal\dd_metrics\Entity\DdCampaignMetrics;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DdMetricsEvent
 * @package Drupal\dd_metrics
 */
class DdMetricsEvent extends Event {
  protected $campaignMetric;
  protected $actionMetric;

  /**
   * DdMetricsEvent constructor.
   *
   * @param DdCampaignMetrics $campaign_metric
   *   Campaign Metric Entity.
   */
  public function __construct(DdCampaignMetrics $campaign_metric = NULL) {
    $this->campaignMetric = $campaign_metric;
  }

  /**
   * Set Campaign Metric entity.
   *
   * @param DdCampaignMetrics $campaign_metric
   *   Campaign Metric Entity
   */
  public function setCampaignMetric(DdCampaignMetrics $campaign_metric) {
    $this->campaignMetric = $campaign_metric;
  }

  /**
   * Get Campaign Metric entity.
   *
   * @return DdCampaignMetrics
   *   Entity
   */
  public function getCampaignMetric() {
    return $this->campaignMetric;
  }

  /**
   * Set Action Metric entity.
   *
   * @param DdActionMetrics $action_metric
   *   Action Metric Entity
   */
  public function setActionMetric(DdActionMetrics $action_metric) {
    $this->actionMetric = $action_metric;
  }

  /**
   * Get Action Metric entity.
   *
   * @return DdActionMetrics
   *   Entity
   */
  public function getActionMetric() {
    return $this->actionMetric;
  }
}
