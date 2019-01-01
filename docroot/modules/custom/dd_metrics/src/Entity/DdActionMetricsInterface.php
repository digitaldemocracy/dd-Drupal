<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Action Metrics entities.
 *
 * @ingroup dd_metrics
 */
interface DdActionMetricsInterface extends  ContentEntityInterface, EntityChangedInterface {

  // Add get/set methods for your configuration properties here.


  /**
   * Gets the DD Action Metrics campaign ID.
   *
   * @return int
   *   Campaign ID of the DD Action Metrics.
   */
  public function getCampaignId();

  /**
   * Sets the DD Action Metrics campaign iD.
   *
   * @param int $id
   *   The DD Action Metrics campaign ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCampaignId($id);

  /**
   * Gets the DD Action Metrics Campaign Action ID.
   *
   * @return int
   *   Campaign Action ID of the DD Action Metrics.
   */
  public function getCampaignActionId();

  /**
   * Sets the DD Action Metrics Campaign Action iD.
   *
   * @param int $id
   *   The DD Action Metrics Campaign Action ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCampaignActionId($id);

  /**
   * Gets the DD Action Metrics Campaign Action Paragraphs ID.
   *
   * @return int
   *   Campaign Action Paragraphs ID of the DD Action Metrics.
   */
  public function getCampaignActionParagraphsId();

  /**
   * Sets the DD Action Metrics Campaign Action Paragraphs ID.
   *
   * @param int $id
   *   The DD Action Metrics Campaign Action Paragraph ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCampaignActionParagraphsId($id);

  /**
   * Gets the DD Action Metrics action ID.
   *
   * @return string
   *   Action ID of the DD Action Metrics.
   */
  public function getActionId();

  /**
   * Sets the DD Action Metrics action iD.
   *
   * @param string $action_id
   *   The DD Action Metrics action ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setActionId($action_id);

  /**
   * Gets the DD Action Metrics Campaign Visitor ID.
   *
   * @return int
   *   Campaign Visitor ID of the DD Action Metrics.
   */
  public function getCampaignVisitorId();

  /**
   * Sets the DD Action Metrics Campaign Visitor ID.
   *
   * @param int $visitor_id
   *   The DD Action Metrics Campaign Visitor ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCampaignVisitorId($visitor_id);

  /**
   * Gets the DD Action Metrics Action Conversion.
   *
   * @return bool
   *   Action Conversion of the DD Action Metrics.
   */
  public function isActionConverted();

  /**
   * Sets the DD Action Metrics Action Conversion.
   *
   * @param bool $action_conversion
   *   The DD Action Metrics Action Conversion.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setActionConversion($action_conversion);


  /**
   * Gets the DD Target Metrics target PID.
   *
   * @return int
   *   Target PID of the DD Target Metrics.
   */
  public function getTargetPid();

  /**
   * Sets the DD Target Metrics target PID.
   *
   * @param int $target_pid
   *   The DD Target Metrics target PID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setTargetPid($target_pid);

  /**
   * Gets the DD Action Metrics whitelabel ID.
   *
   * @return string
   *   Whitelabel ID of the DD Action Metrics.
   */
  public function getWhitelabelId();

  /**
   * Sets the DD Action Metrics whitelabel iD.
   *
   * @param string $whitelabel_id
   *   The DD Action Metrics whitelabel ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setWhitelabelId($whitelabel_id);

  /**
   * Gets the DD Action Metrics creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Action Metrics.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Action Metrics creation timestamp.
   *
   * @param int $timestamp
   *   The DD Action Metrics creation timestamp.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCreatedTime($timestamp);


  /**
   * Returns the DD Action Metrics published status indicator.
   *
   * Unpublished DD Action Metrics are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Action Metrics is published.
   */
  public function isPublished();
}
