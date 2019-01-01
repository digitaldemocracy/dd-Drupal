<?php

namespace Drupal\dd_metrics;

use Drupal\user\Entity\User;

/**
 * Interface DdMetricsServiceInterface.
 *
 * @package Drupal\dd_metrics
 */
interface DdMetricsServiceInterface {

  /**
   * Increments a Campaign Metric Type log.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $metric_type
   *   Metric Type, from DdCampaignMetricTypes constants.
   *
   * @return int
   *   Campaign Metric row ID
   *
   * @throws \InvalidArgumentException, \BadMethodCallException
   */
  public function logCampaignMetric($campaign_id, $metric_type);

  /**
   * Logs an Action Metric.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $action_id
   *   Action ID (paragraph machine name)
   * @param int $campaign_action_id
   *   Campaign Action ID
   * @param int $campaign_action_paragraphs_id
   *   Campaign Action Paragraphs ID
   * @param int $target_pid
   *   Target Legislator PID
   * @param User $visitor_user
   *   Visitor User
   * @param bool $action_conversion
   *   TRUE if action has converted, FALSE otherwise (default).
   *
   * @return int
   *   Action Metric row ID
   *
   * @throws \InvalidArgumentException, \BadMethodCallException
   */
  public function logActionMetric($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL, $visitor_user = NULL, $action_conversion = FALSE);

  /**
   * Logs an Action Conversion, matching all criteria and session ID.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $action_id
   *   Action ID (paragraph machine name)
   * @param int $campaign_action_id
   *   Campaign Action ID
   * @param int $campaign_action_paragraphs_id
   *   Campaign Action Paragraphs ID
   * @param int $target_pid
   *   Target Legislator PID
   * @param User $visitor_user
   *   Visitor User
   *
   * @return int
   *   Action Metric row ID updated, or FALSE if not matched.
   *
   * @throws \InvalidArgumentException, \BadMethodCallException
   */
  public function logActionConversion($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL, $visitor_user = NULL);
}
