<?php

namespace Drupal\dd_clip\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Clip entities.
 *
 * @ingroup dd_clip
 */
interface DdClipInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Clip name.
   *
   * @return string
   *   Name of the DD Clip.
   */
  public function getName();

  /**
   * Sets the DD Clip name.
   *
   * @param string $name
   *   The DD Clip name.
   *
   * @return \Drupal\dd_clip\Entity\DdClipInterface
   *   The called DD Clip entity.
   */
  public function setName($name);

  /**
   * Gets the DD Clip creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Clip.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Clip creation timestamp.
   *
   * @param int $timestamp
   *   The DD Clip creation timestamp.
   *
   * @return \Drupal\dd_clip\Entity\DdClipInterface
   *   The called DD Clip entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Clip published status indicator.
   *
   * Unpublished DD Clip are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Clip is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a DD Clip.
   *
   * @param bool $published
   *   TRUE to set this DD Clip to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_clip\Entity\DdClipInterface
   *   The called DD Clip entity.
   */
  public function setPublished($published);

}
