<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Payor entities.
 *
 * @ingroup dd_gift_contribution
 */
interface DdPayorInterface extends ContentEntityInterface {

  /**
   * Gets the DD Payor name.
   *
   * @return string
   *   Name of the DD Payor.
   */
  public function getName();

  /**
   * Sets the DD Payor name.
   *
   * @param string $name
   *   The DD Payor name.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdPayorInterface
   *   The called DD Payor entity.
   */
  public function setName($name);

  /**
   * Gets the DD Payor city.
   *
   * @return string
   *   City of the DD Payor.
   */
  public function getCity();

  /**
   * Sets the DD Payor city.
   *
   * @param string $city
   *   The DD Payor city.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdPayorInterface
   *   The called DD Payor entity.
   */
  public function setCity($city);
}
