<?php

namespace Drupal\dd_account_dashboard\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Saved Content entities.
 *
 * @ingroup dd_account_dashboard
 */
interface DdSavedContentInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Saved Content name.
   *
   * @return string
   *   Name of the DD Saved Content.
   */
  public function getName();

  /**
   * Sets the DD Saved Content name.
   *
   * @param string $name
   *   The DD Saved Content name.
   *
   * @return \Drupal\dd_account_dashboard\Entity\DdSavedContentInterface
   *   The called DD Saved Content entity.
   */
  public function setName($name);

  /**
   * Gets the DD Saved Content creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Saved Content.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Saved Content creation timestamp.
   *
   * @param int $timestamp
   *   The DD Saved Content creation timestamp.
   *
   * @return \Drupal\dd_account_dashboard\Entity\DdSavedContentInterface
   *   The called DD Saved Content entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Saved Content published status indicator.
   *
   * Unpublished DD Saved Content are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Saved Content is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a DD Saved Content.
   *
   * @param bool $published
   *   TRUE to set this DD Saved Content to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_account_dashboard\Entity\DdSavedContentInterface
   *   The called DD Saved Content entity.
   */
  public function setPublished($published);

}
