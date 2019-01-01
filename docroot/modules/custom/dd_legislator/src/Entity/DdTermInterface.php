<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Term entities.
 *
 * @ingroup dd_legislator
 */
interface DdTermInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Term pid.
   *
   * @return string
   *   Pid of the DD Term.
   */
  public function getPid();

  /**
   * Sets the DD Term pid.
   *
   * @param string $pid
   *   The DD Term pid.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Term year.
   *
   * @return string
   *   Year of the DD Term.
   */
  public function getYear();

  /**
   * Sets the DD Term year.
   *
   * @param string $year
   *   The DD Term year.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setYear($year);

  /**
   * Gets the DD Term district.
   *
   * @return string
   *   District of the DD Term.
   */
  public function getDistrict();

  /**
   * Sets the DD Term district.
   *
   * @param string $district
   *   The DD Term district.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setDistrict($district);

  /**
   * Gets the DD Term house.
   *
   * @return string
   *   House of the DD Term.
   */
  public function getHouse();

  /**
   * Sets the DD Term house.
   *
   * @param string $house
   *   The DD Term house.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD Term party.
   *
   * @return string
   *   Party of the DD Term.
   */
  public function getParty();

  /**
   * Sets the DD Term party.
   *
   * @param string $party
   *   The DD Term party.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setParty($party);

  /**
   * Gets the DD Term name.
   *
   * @return string
   *   StartDate of the DD Term.
   */
  public function getStartDate();

  /**
   * Sets the DD Term startDate.
   *
   * @param string $start_date
   *   The DD Term startDate.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setStartDate($start_date);

  /**
   * Gets the DD Term endDate.
   *
   * @return string
   *   EndDate of the DD Term.
   */
  public function getEndDate();

  /**
   * Sets the DD Term endDate.
   *
   * @param string $end_date
   *   The DD Term endDate.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setEndDate($end_date);

  /**
   * Gets the DD Term caucus.
   *
   * @return string
   *   Caucus of the DD Term.
   */
  public function getCaucus();

  /**
   * Sets the DD Term caucus.
   *
   * @param string $caucus
   *   The DD Term caucus.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setCaucus($caucus);

  /**
   * Gets the DD Term currentTerm.
   *
   * @return string
   *   CurrentTerm of the DD Term.
   */
  public function getCurrentTerm();

  /**
   * Sets the DD Term currentTerm.
   *
   * @param string $current_term
   *   The DD Term currentTerm.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called DD Term entity.
   */
  public function setCurrentTerm($current_term);

  /**
   * Gets the Term official bio.
   *
   * @return string
   *   Official Bio of the Term.
   */
  public function getOfficialBio();

  /**
   * Sets the Term official bio.
   *
   * @param string $official_bio
   *   The Term official bio.
   *
   * @return \Drupal\dd_legislator\Entity\DdTermInterface
   *   The called Term entity.
   */
  public function setOfficialBio($official_bio);
}
