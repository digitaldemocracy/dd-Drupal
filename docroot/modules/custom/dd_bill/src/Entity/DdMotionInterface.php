<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Motion entities.
 *
 * @ingroup dd_bill
 */
interface DdMotionInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Motion text.
   *
   * @return string
   *   Text of the DD Motion.
   */
  public function getText();

  /**
   * Sets the DD Motion text.
   *
   * @param string $text
   *   The DD Motion text.
   *
   * @return \Drupal\dd_bill\Entity\DdMotionInterface
   *   The called DD Motion entity.
   */
  public function setText($text);

  /**
   * Gets the DD Motion doPass.
   *
   * @return string
   *   DoPass of the DD Motion.
   */
  public function getDoPass();

  /**
   * Sets the DD Motion doPass.
   *
   * @param string $do_pass
   *   The DD Motion doPass.
   *
   * @return \Drupal\dd_bill\Entity\DdMotionInterface
   *   The called DD Motion entity.
   */
  public function setDoPass($do_pass);
}
