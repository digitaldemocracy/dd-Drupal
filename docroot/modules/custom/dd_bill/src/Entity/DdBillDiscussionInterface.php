<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Bill Discussion entities.
 *
 * @ingroup dd_bill
 */
interface DdBillDiscussionInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Bill Discussion bid.
   *
   * @return string
   *   Bid of the DD Bill Discussion.
   */
  public function getBid();

  /**
   * Sets the DD Bill Discussion bid.
   *
   * @param string $bid
   *   The DD Bill Discussion bid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD Bill Discussion bid_dr_id.
   *
   * @return string
   *   Bid DrIdof the DD Bill Discussion.
   */
  public function getBidDrId();

  /**
   * Sets the DD Bill Discussion bid_dr_id.
   *
   * @param string $bid
   *   The DD Bill Discussion bid_dr_id.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setBidDrId($bid_dr_id);

  /**
   * Gets the DD Bill Discussion hid.
   *
   * @return string
   *   Hid of the DD Bill Discussion.
   */
  public function getHid();

  /**
   * Sets the DD Bill Discussion hid.
   *
   * @param string $hid
   *   The DD Bill Discussion hid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD Bill Discussion startVideo.
   *
   * @return string
   *   StartVideo of the DD Bill Discussion.
   */
  public function getStartVideo();

  /**
   * Sets the DD Bill Discussion startVideo.
   *
   * @param string $start_video
   *   The DD Bill Discussion startVideo.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setStartVideo($start_video);

  /**
   * Gets the DD Bill Discussion startTime.
   *
   * @return string
   *   StartTime of the DD Bill Discussion.
   */
  public function getStartTime();

  /**
   * Sets the DD Bill Discussion startTime.
   *
   * @param string $start_time
   *   The DD Bill Discussion startTime.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setStartTime($start_time);

  /**
   * Gets the DD Bill Discussion endVideo.
   *
   * @return string
   *   EndVideo of the DD Bill Discussion.
   */
  public function getEndVideo();

  /**
   * Sets the DD Bill Discussion endVideo.
   *
   * @param string $end_video
   *   The DD Bill Discussion endVideo.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setEndVideo($end_video);

  /**
   * Gets the DD Bill Discussion endTime.
   *
   * @return string
   *   EndTime of the DD Bill Discussion.
   */
  public function getEndTime();

  /**
   * Sets the DD Bill Discussion endTime.
   *
   * @param string $end_time
   *   The DD Bill Discussion endTime.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setEndTime($end_time);

  /**
   * Gets the DD Bill Discussion numVideos.
   *
   * @return string
   *   NumVideos of the DD Bill Discussion.
   */
  public function getNumVideos();

  /**
   * Sets the DD Bill Discussion numVideos.
   *
   * @param string $num_videos
   *   The DD Bill Discussion numVideos.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setNumVideos($num_videos);

  /**
   * Gets the DD Bill Discussion speakers.
   *
   * @return string
   *   Speakers of the DD Bill Discussion.
   */
  public function getSpeakers();

  /**
   * Sets the DD Bill Discussion speakers.
   *
   * @param string $speakers
   *   The DD Bill Discussion speakers.
   *
   * @return \Drupal\dd_bill\Entity\DdBillDiscussionInterface
   *   The called DD Bill Discussion entity.
   */
  public function setSpeakers($speakers);

}
