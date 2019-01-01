<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Committee Authors entities.
 *
 * @ingroup dd_committee
 */
interface DdCommitteeAuthorsInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Committee Authors cid.
   *
   * @return string
   *   cid of the DD Committee Authors.
   */
  public function getCid();

  /**
   * Sets the DD Committee Authors cid.
   *
   * @param int $cid
   *   The DD Committee Authors cid.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeAuthorsInterface
   *   The called DD Committee Authors entity.
   */
  public function setCid($cid);

  /**
   * Gets the DD Committee Authors bid.
   *
   * @return string
   *   bid of the DD Committee Authors.
   */
  public function getBid();

  /**
   * Sets the DD Committee Authors bid.
   *
   * @param int $bid
   *   The DD Committee Authors bid.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeAuthorsInterface
   *   The called DD Committee Authors entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD Committee Authors vid.
   *
   * @return string
   *   vid of the DD Committee Authors.
   */
  public function getVid();

  /**
   * Sets the DD Committee Authors vid.
   *
   * @param int $vid
   *   The DD Committee Authors vid.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeAuthorsInterface
   *   The called DD Committee Authors entity.
   */
  public function setVid($vid);
}
