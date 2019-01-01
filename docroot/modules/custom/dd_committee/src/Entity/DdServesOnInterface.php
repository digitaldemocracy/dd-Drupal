<?php

namespace Drupal\dd_committee\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD ServesOn entities.
 *
 * @ingroup dd_committee
 */
interface DdServesOnInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD ServesOn pid.
   *
   * @return string
   *   Pid of the DD ServesOn.
   */
  public function getPid();

  /**
   * Sets the DD ServesOn pid.
   *
   * @param string $pid
   *   The DD ServesOn pid.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD ServesOn cid.
   *
   * @return string
   *   Cid of the DD ServesOn.
   */
  public function getCid();

  /**
   * Sets the DD ServesOn cid.
   *
   * @param string $cid
   *   The DD ServesOn cid.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setCid($cid);

  /**
   * Gets the DD ServesOn year.
   *
   * @return string
   *   Year of the DD ServesOn.
   */
  public function getYear();

  /**
   * Sets the DD ServesOn year.
   *
   * @param string $year
   *   The DD ServesOn year.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setYear($year);

  /**
   * Gets the DD ServesOn house.
   *
   * @return string
   *   House of the DD ServesOn.
   */
  public function getHouse();

  /**
   * Sets the DD ServesOn house.
   *
   * @param string $house
   *   The DD ServesOn house.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD ServesOn oid.
   *
   * @return string
   *   Oid of the DD ServesOn.
   */
  public function getOid();

  /**
   * Sets the DD ServesOn oid.
   *
   * @param string $oid
   *   The DD ServesOn oid.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD ServesOn position.
   *
   * @return string
   *   Position of the DD ServesOn.
   */
  public function getPosition();

  /**
   * Sets the DD ServesOn position.
   *
   * @param string $position
   *   The DD ServesOn position.
   *
   * @return \Drupal\dd_committee\Entity\DdServesOnInterface
   *   The called DD ServesOn entity.
   */
  public function setPosition($position);
}
