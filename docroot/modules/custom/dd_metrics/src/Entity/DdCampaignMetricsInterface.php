<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface for defining DD Campaign Metrics entities.
 *
 * @ingroup dd_metrics
 */
interface DdCampaignMetricsInterface extends ContentEntityInterface, EntityChangedInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Campaign Metrics campaign ID.
   *
   * @return int
   *   Campaign ID of the DD Campaign Metrics.
   */
  public function getCampaignId();

  /**
   * Sets the DD Campaign  Metrics campaign iD.
   *
   * @param int $id
   *   The DD Campaign Metrics campaign ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignMetricsInterface
   *   The called DD Campaign Metrics entity.
   */
  public function setCampaignId($id);

  /**
   * Gets the DD Campaign Metrics Type.
   *
   * @return string
   *   Metric Type of the DD Campaign Metrics.
   */
  public function getMetricType();

  /**
   * Sets the DD Campaign Metrics Type.
   *
   * @param string $type
   *   The DD Campaign Metrics Type.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignMetricsInterface
   *   The called DD Campaign Metrics entity.
   */
  public function setMetricType($type);

  /**
   * Gets the DD Campaign Metrics Total Count.
   *
   * @return int
   *   Total Count of the DD Campaign Metrics.
   */
  public function getTotalCount();

  /**
   * Sets the DD Campaign Metrics Total Count.
   *
   * @param int $total_count
   *   The DD Campaign Metrics Total Count.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignMetricsInterface
   *   The called DD Campaign Metrics entity.
   */
  public function setTotalCount($total_count);

  /**
   * Gets the DD Campaign Metrics whitelabel ID.
   *
   * @return string
   *   Whitelabel ID of the DD Campaign Metrics.
   */
  public function getWhitelabelId();

  /**
   * Sets the Campaign Metrics whitelabel iD.
   *
   * @param string $whitelabel_id
   *   The DD Campaign Metrics whitelabel ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignMetricsInterface
   *   The called DD Campaign Metrics entity.
   */
  public function setWhitelabelId($whitelabel_id);

  /**
   * Gets the DD Campaign Metrics creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Campaign Metrics.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Campaign Metrics creation timestamp.
   *
   * @param int $timestamp
   *   The DD Campaign Metrics creation timestamp.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignMetricsInterface
   *   The called DD Campaign Metrics entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Campaign Metrics published status indicator.
   *
   * Unpublished DD Campaign Metrics are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Campaign Metrics is published.
   */
  public function isPublished();
}
