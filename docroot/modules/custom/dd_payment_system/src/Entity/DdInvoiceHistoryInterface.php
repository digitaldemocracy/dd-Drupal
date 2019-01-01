<?php

namespace Drupal\dd_payment_system\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dd invoice history entities.
 *
 * @ingroup dd_payment_system
 */
interface DdInvoiceHistoryInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Dd invoice history name.
   *
   * @return string
   *   Name of the Dd invoice history.
   */
  public function getName();

  /**
   * Sets the Dd invoice history name.
   *
   * @param string $name
   *   The Dd invoice history name.
   *
   * @return \Drupal\dd_payment_system\Entity\DdInvoiceHistoryInterface
   *   The called Dd invoice history entity.
   */
  public function setName($name);

  /**
   * Gets the Dd invoice history creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dd invoice history.
   */
  public function getCreatedTime();

  /**
   * Sets the Dd invoice history creation timestamp.
   *
   * @param int $timestamp
   *   The Dd invoice history creation timestamp.
   *
   * @return \Drupal\dd_payment_system\Entity\DdInvoiceHistoryInterface
   *   The called Dd invoice history entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dd invoice history published status indicator.
   *
   * Unpublished Dd invoice history are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dd invoice history is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dd invoice history.
   *
   * @param bool $published
   *   TRUE to set this Dd invoice history to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_payment_system\Entity\DdInvoiceHistoryInterface
   *   The called Dd invoice history entity.
   */
  public function setPublished($published);

}
