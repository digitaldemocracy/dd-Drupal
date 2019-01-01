<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Person Classifications entities.
 *
 * @ingroup dd_person
 */
interface DdPersonClassificationsInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Person Classifications pid.
   *
   * @return string
   *   Pid of the DD Person Classifications.
   */
  public function getPid();

  /**
   * Sets the DD Person Classifications pid.
   *
   * @param string $pid
   *   The DD Person Classifications pid.
   *
   * @return \Drupal\dd_person\Entity\DdPersonClassifications
   *   The called DD Person Classifications entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Person Classifications Person Type.
   *
   * @return string
   *   Person Type of the DD Person Classifications.
   */
  public function getPersonType();

  /**
   * Sets the DD Person Classifications Person Type.
   *
   * @param string $person_type
   *   The DD Person Classifications Person Type.
   *
   * @return \Drupal\dd_person\Entity\DdPersonClassifications
   *   The called DD Person Classifications entity.
   */
  public function setPersonType($person_type);

  /**
   * Gets the DD Person Classification SpecificYear.
   *
   * @return string
   *   SpecificYear of the DD Person Classification.
   */
  public function getSpecificYear();

  /**
   * Sets the DD Person Classification SpecificYear.
   *
   * @param string $specific_year
   *   The DD Person Classification SpecificYear.
   *
   * @return \Drupal\dd_person\Entity\DdPersonClassifications
   *   The called DD Person Classification entity.
   */
  public function setSpecificYear($specific_year);

  /**
   * Gets the DD Person Classification Session Year.
   *
   * @return string
   *   Session Year of the DD Person Classification.
   */
  public function getSessionYear();

  /**
   * Sets the DD Person Classification Session Year.
   *
   * @param string $session_year
   *   The DD Person Classification Session Year.
   *
   * @return \Drupal\dd_person\Entity\DdPersonClassifications
   *   The called DD Person Classification entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Person Classification is_current.
   *
   * @return string
   *   Is_current of the DD Person Classification.
   */
  public function getIsCurrent();

  /**
   * Sets the DD Person Classification is_current.
   *
   * @param string $is_current
   *   The DD Person Classification is_current.
   *
   * @return \Drupal\dd_person\Entity\DdPersonClassifications
   *   The called DD Person Classification entity.
   */
  public function setIsCurrent($is_current);

}
