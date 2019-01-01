<?php

namespace Drupal\dd_person\Entity;

/**
 * Provides an interface for defining DD General Public entities.
 *
 * @ingroup dd_person
 */
interface DdGeneralPublicInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD General Public pid.
   *
   * @return string
   *   Pid of the DD General Public.
   */
  public function getPid();

  /**
   * Sets the DD General Public pid.
   *
   * @param string $pid
   *   The DD General Public pid.
   *
   * @return \Drupal\dd_person\Entity\DdGeneralPublicInterface
   *   The called DD General Public entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD General Public position.
   *
   * @return string
   *   Position of the DD General Public.
   */
  public function getPosition();

  /**
   * Sets the DD General Public position.
   *
   * @param string $position
   *   The DD General Public position.
   *
   * @return \Drupal\dd_person\Entity\DdGeneralPublicInterface
   *   The called DD General Public entity.
   */
  public function setPosition($position);

  /**
   * Gets the DD General Public hid.
   *
   * @return string
   *   Hid of the DD General Public.
   */
  public function getHid();

  /**
   * Sets the DD General Public hid.
   *
   * @param string $hid
   *   The DD General Public hid.
   *
   * @return \Drupal\dd_person\Entity\DdGeneralPublicInterface
   *   The called DD General Public entity.
   */
  public function setHid($hid);


  /**
   * Gets the DD General Public did.
   *
   * @return string
   *   Did of the DD General Public.
   */
  public function getDid();

  /**
   * Sets the DD General Public did.
   *
   * @param string $did
   *   The DD General Public did.
   *
   * @return \Drupal\dd_person\Entity\DdGeneralPublicInterface
   *   The called DD General Public entity.
   */
  public function setDid($did);

  /**
   * Gets the DD General Public oid.
   *
   * @return string
   *   Oid of the DD General Public.
   */
  public function getOid();

  /**
   * Sets the DD General Public oid.
   *
   * @param string $oid
   *   The DD General Public oid.
   *
   * @return \Drupal\dd_person\Entity\DdGeneralPublicInterface
   *   The called DD General Public entity.
   */
  public function setOid($oid);
}
