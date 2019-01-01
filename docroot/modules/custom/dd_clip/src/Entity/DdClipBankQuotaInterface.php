<?php

namespace Drupal\dd_clip\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Clip Bank Quota entities.
 *
 * @ingroup dd_clip
 */
interface DdClipBankQuotaInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Clip Bank Quota name.
   *
   * @return string
   *   Name of the DD Clip Bank Quota.
   */
  public function getName();

  /**
   * Sets the DD Clip Bank Quota name.
   *
   * @param string $name
   *   The DD Clip Bank Quota name.
   *
   * @return \Drupal\dd_clip\Entity\DdClipBankQuotaInterface
   *   The called DD Clip Bank Quota entity.
   */
  public function setName($name);

  /**
   * Gets the DD Clip Bank Quota creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Clip Bank Quota.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Clip Bank Quota creation timestamp.
   *
   * @param int $timestamp
   *   The DD Clip Bank Quota creation timestamp.
   *
   * @return \Drupal\dd_clip\Entity\DdClipBankQuotaInterface
   *   The called DD Clip Bank Quota entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Clip Bank Quota published status indicator.
   *
   * Unpublished DD Clip Bank Quota are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Clip Bank Quota is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a DD Clip Bank Quota.
   *
   * @param bool $published
   *   TRUE to set this DD Clip Bank Quota to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dd_clip\Entity\DdClipBankQuotaInterface
   *   The called DD Clip Bank Quota entity.
   */
  public function setPublished($published);

}
