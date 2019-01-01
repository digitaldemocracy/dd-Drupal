<?php

namespace Drupal\dd_payment_system\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Subscription Plan entities.
 *
 * @ingroup dd_payment_system
 */
interface DdSubscriptionPlanInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Subscription Plan name.
   *
   * @return string
   *   Name of the DD Subscription Plan.
   */
  public function getName();

  /**
   * Sets the DD Subscription Plan name.
   *
   * @param string $name
   *   The DD Subscription Plan name.
   *
   * @return \Drupal\dd_payment_system\Entity\DdSubscriptionPlanInterface
   *   The called DD Subscription Plan entity.
   */
  public function setName($name);

  /**
   * Gets the DD Subscription Plan creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Subscription Plan.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Subscription Plan creation timestamp.
   *
   * @param int $timestamp
   *   The DD Subscription Plan creation timestamp.
   *
   * @return \Drupal\dd_payment_system\Entity\DdSubscriptionPlanInterface
   *   The called DD Subscription Plan entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Subscription Plan published status indicator.
   *
   * Unpublished DD Subscription Plan are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Subscription Plan is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a DD Subscription Plan.
   *
   * @param bool $published
   *   TRUE to set this DD Subscription Plan to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_payment_system\Entity\DdSubscriptionPlanInterface
   *   The called DD Subscription Plan entity.
   */
  public function setPublished($published);

}
