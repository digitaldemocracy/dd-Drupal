<?php

namespace Drupal\dd_bill\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Bill Version entities.
 *
 * @ingroup dd_bill
 */
interface DdBillVersionInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Bill Version vid.
   *
   * @return string
   *   Vid of the DD Bill Version.
   */
  public function getVid();

  /**
   * Sets the DD Bill Version vid.
   *
   * @param string $vid
   *   The DD Bill Version vid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setVid($vid);

  /**
   * Gets the DD Bill Version bid.
   *
   * @return string
   *   Bid of the DD Bill Version.
   */
  public function getBid();

  /**
   * Sets the DD Bill Version bid.
   *
   * @param string $bid
   *   The DD Bill Version bid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD Bill Version date.
   *
   * @return string
   *   Date of the DD Bill Version.
   */
  public function getDate();

  /**
   * Sets the DD Bill Version date.
   *
   * @param string $date
   *   The DD Bill Version date.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setDate($date);

  /**
   * Gets the DD Bill Version billState.
   *
   * @return string
   *   BillState of the DD Bill Version.
   */
  public function getBillState();

  /**
   * Sets the DD Bill Version billState.
   *
   * @param string $bill_state
   *   The DD Bill Version billState.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setBillState($bill_state);

  /**
   * Gets the DD Bill Version subject.
   *
   * @return string
   *   Subject of the DD Bill Version.
   */
  public function getSubject();

  /**
   * Sets the DD Bill Version subject.
   *
   * @param string $subject
   *   The DD Bill Version subject.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setSubject($subject);

  /**
   * Gets the DD Bill Version appropriation.
   *
   * @return string
   *   Appropriation of the DD Bill Version.
   */
  public function getAppropriation();

  /**
   * Sets the DD Bill Version appropriation.
   *
   * @param string $appropriation
   *   The DD Bill Version appropriation.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setAppropriation($appropriation);
  /**
   * Gets the DD Bill Version substantiveChanges.
   *
   * @return string
   *   SubstantiveChanges of the DD Bill Version.
   */
  public function getSubstantiveChanges();

  /**
   * Sets the DD Bill Version substantiveChanges.
   *
   * @param string $substantive_changes
   *   The DD Bill Version substantiveChanges.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setSubstantiveChanges($substantive_changes);

  /**
   * Gets the DD Bill Version title.
   *
   * @return string
   *   Title of the DD Bill Version.
   */
  public function getTitle();

  /**
   * Sets the DD Bill Version title.
   *
   * @param string $title
   *   The DD Bill Version title.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setTitle($title);

  /**
   * Gets the DD Bill Version digest.
   *
   * @return string
   *   Digest of the DD Bill Version.
   */
  public function getDigest();

  /**
   * Sets the DD Bill Version digest.
   *
   * @param string $digest
   *   The DD Bill Version digest.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setDigest($digest);

  /**
   * Gets the DD Bill Version text.
   *
   * @return string
   *   Text of the DD Bill Version.
   */
  public function getText();

  /**
   * Sets the DD Bill Version text.
   *
   * @param string $text
   *   The DD Bill Version text.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setText($text);

  /**
   * Gets the DD Bill Version Bid Drupal ID.
   *
   * @return string
   *   BidDrId of the DD Bill Version.
   */
  public function getBidDrId();

  /**
   * Sets the DD Bill Version Bid Drupal ID.
   *
   * @param string $bid_dr_id
   *   The DD Bill Version bid Drupal ID.
   *
   * @return \Drupal\dd_bill\Entity\DdBillVersionInterface
   *   The called DD Bill Version entity.
   */
  public function setBidDrId($bid_dr_id);
}
