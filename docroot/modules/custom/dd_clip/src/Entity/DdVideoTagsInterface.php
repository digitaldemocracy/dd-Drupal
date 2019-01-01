<?php

namespace Drupal\dd_clip\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Video Tags entities.
 *
 * @ingroup dd_clip
 */
interface DdVideoTagsInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Video Tags name.
   *
   * @return string
   *   Tag of the DD Video Tags.
   */
  public function getTag();

  /**
   * Sets the DD Video Tags name.
   *
   * @param string $name
   *   The DD Video Tags name.
   *
   * @return \Drupal\dd_clip\Entity\DdVideoTagsInterface
   *   The called DD Video Tags entity.
   */
  public function setTag($name);

  /**
   * Gets the DD Video Tags creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Video Tags.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Video Tags creation timestamp.
   *
   * @param int $timestamp
   *   The DD Video Tags creation timestamp.
   *
   * @return \Drupal\dd_clip\Entity\DdVideoTagsInterface
   *   The called DD Video Tags entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Video Tags published status indicator.
   *
   * Unpublished DD Video Tags are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Video Tags is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a DD Video Tags.
   *
   * @param bool $published
   *   TRUE to set this DD Video Tags to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_clip\Entity\DdVideoTagsInterface
   *   The called DD Video Tags entity.
   */
  public function setPublished($published);

}
