<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Committee entities.
 *
 * @ingroup dd_committee
 */
interface DdCommitteeHearingsInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Committee cid.
   *
   * @return string
   *   Cid of the DD Committee.
   */
  public function getCid();

  /**
   * Sets the DD Committee cid.
   *
   * @param int $cid
   *   The DD Committee cid.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeHearingsInterface
   *   The called DD Committee entity.
   */
  public function setCid($cid);

  /**
   * Gets the DD Committee hid.
   *
   * @return string
   *   Hid of the DD Committee.
   */
  public function getHid();

  /**
   * Sets the DD Committee hid.
   *
   * @param int $hid
   *   The DD Committee hid.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeHearingsInterface
   *   The called DD Committee entity.
   */
  public function setHid($hid);
  
  /**
   * Returns the DD Committee published status indicator.
   *
   * Unpublished DD Committee are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Committee is published.
   */
  public function isPublished();
}
