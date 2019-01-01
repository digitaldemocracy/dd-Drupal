<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Authors entities.
 *
 * @ingroup dd_person
 */
interface DdAuthorsInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Authors pid.
   *
   * @return string
   *   Pid of the DD Authors.
   */
  public function getPid();

  /**
   * Sets the DD Authors pid.
   *
   * @param string $pid
   *   The DD Authors pid.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Authors bid.
   *
   * @return string
   *   Bid of the DD Authors.
   */
  public function getBid();

  /**
   * Sets the DD Authors bid.
   *
   * @param string $bid
   *   The DD Authors bid.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD Authors vid.
   *
   * @return string
   *   Vid of the DD Authors.
   */
  public function getVid();

  /**
   * Sets the DD Authors vid.
   *
   * @param string $vid
   *   The DD Authors vid.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setVid($vid);

  /**
   * Gets the DD Authors contribution.
   *
   * @return string
   *   Contribution of the DD Authors.
   */
  public function getContribution();

  /**
   * Sets the DD Authors contribution.
   *
   * @param string $contribution
   *   The DD Authors contribution.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setContribution($contribution);

  /**
   * Gets the DD Authors bid_dr_id.
   *
   * @return string
   *   BidDrId of the DD Authors.
   */
  public function getBidDrId();

  /**
   * Sets the DD Authors bid_dr_id.
   *
   * @param string $bid_dr_id
   *   The DD Authors bid_dr_id.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setBidDrId($bid_dr_id);

  /**
   * Gets the DD Authors vid_dr_id.
   *
   * @return string
   *   VidDrId of the DD Authors.
   */
  public function getVidDrId();

  /**
   * Sets the DD Authors vid_dr_id.
   *
   * @param string $vid_dr_id
   *   The DD Authors vid_dr_id.
   *
   * @return \Drupal\dd_person\Entity\DdAuthorsInterface
   *   The called DD Authors entity.
   */
  public function setVidDrId($vid_dr_id);
}
