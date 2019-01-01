<?php

namespace Drupal\dd_fax_service\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dd fax service history entities.
 *
 * @ingroup dd_fax_service
 */
interface DdFaxServiceHistoryInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Dd fax service history name.
   *
   * @return string
   *   Name of the Dd fax service history.
   */
  public function getName();

  /**
   * Sets the Dd fax service history name.
   *
   * @param string $name
   *   The Dd fax service history name.
   *
   * @return \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface
   *   The called Dd fax service history entity.
   */
  public function setName($name);

  /**
   * Gets the Dd fax service history creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dd fax service history.
   */
  public function getCreatedTime();

  /**
   * Sets the Dd fax service history creation timestamp.
   *
   * @param int $timestamp
   *   The Dd fax service history creation timestamp.
   *
   * @return \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface
   *   The called Dd fax service history entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dd fax service history published status indicator.
   *
   * Unpublished Dd fax service history are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dd fax service history is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dd fax service history.
   *
   * @param bool $published
   *   TRUE to set this Dd fax service history to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface
   *   The called Dd fax service history entity.
   */
  public function setPublished($published);

}
