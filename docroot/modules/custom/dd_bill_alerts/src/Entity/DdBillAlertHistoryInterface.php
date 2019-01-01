<?php

namespace Drupal\dd_bill_alerts\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dd bill alert history entities.
 *
 * @ingroup dd_bill_alerts
 */
interface DdBillAlertHistoryInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Dd bill alert history name.
   *
   * @return string
   *   Name of the Dd bill alert history.
   */
  public function getName();

  /**
   * Sets the Dd bill alert history name.
   *
   * @param string $name
   *   The Dd bill alert history name.
   *
   * @return \Drupal\dd_bill_alerts\Entity\DdBillAlertHistoryInterface
   *   The called Dd bill alert history entity.
   */
  public function setName($name);

  /**
   * Gets the Dd bill alert history creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dd bill alert history.
   */
  public function getCreatedTime();

  /**
   * Sets the Dd bill alert history creation timestamp.
   *
   * @param int $timestamp
   *   The Dd bill alert history creation timestamp.
   *
   * @return \Drupal\dd_bill_alerts\Entity\DdBillAlertHistoryInterface
   *   The called Dd bill alert history entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dd bill alert history published status indicator.
   *
   * Unpublished Dd bill alert history are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dd bill alert history is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dd bill alert history.
   *
   * @param bool $published
   *   TRUE to set this Dd bill alert history to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_bill_alerts\Entity\DdBillAlertHistoryInterface
   *   The called Dd bill alert history entity.
   */
  public function setPublished($published);

}
