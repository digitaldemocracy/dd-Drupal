<?php

namespace Drupal\dd_fax_service_payment\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dd fax service payment entity entities.
 *
 * @ingroup dd_fax_service_payment
 */
interface DdFaxServicePaymentEntityInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Dd fax service payment entity name.
   *
   * @return string
   *   Name of the Dd fax service payment entity.
   */
  public function getName();

  /**
   * Sets the Dd fax service payment entity name.
   *
   * @param string $name
   *   The Dd fax service payment entity name.
   *
   * @return \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntityInterface
   *   The called Dd fax service payment entity entity.
   */
  public function setName($name);

  /**
   * Gets the Dd fax service payment entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dd fax service payment entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Dd fax service payment entity creation timestamp.
   *
   * @param int $timestamp
   *   The Dd fax service payment entity creation timestamp.
   *
   * @return \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntityInterface
   *   The called Dd fax service payment entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dd fax service payment entity published status indicator.
   *
   * Unpublished Dd fax service payment entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dd fax service payment entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dd fax service payment entity.
   *
   * @param bool $published
   *   TRUE to set this Dd fax service payment entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntityInterface
   *   The called Dd fax service payment entity entity.
   */
  public function setPublished($published);

}
