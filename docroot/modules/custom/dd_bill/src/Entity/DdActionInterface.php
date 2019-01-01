<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Action entities.
 *
 * @ingroup dd_bill
 */
interface DdActionInterface extends ContentEntityInterface   {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Action bid.
   *
   * @return string
   *   Bid of the DD Action.
   */
  public function getBid();

  /**
   * Sets the DD Action bid.
   *
   * @param string $bid
   *   The DD Action bid.
   *
   * @return \Drupal\dd_bill\Entity\DdActionInterface
   *   The called DD Action entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD Action date.
   *
   * @return string
   *   Date of the DD Action.
   */
  public function getDate();

  /**
   * Sets the DD Action date.
   *
   * @param string $date
   *   The DD Action date.
   *
   * @return \Drupal\dd_bill\Entity\DdActionInterface
   *   The called DD Action entity.
   */
  public function setDate($date);

  /**
   * Gets the DD Action text.
   *
   * @return string
   *   Text of the DD Action.
   */
  public function getText();

  /**
   * Sets the DD Action text.
   *
   * @param string $text
   *   The DD Action text.
   *
   * @return \Drupal\dd_bill\Entity\DdActionInterface
   *   The called DD Action entity.
   */
  public function setText($text);

}
