<?php

namespace Drupal\dd_committee\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD ConsultantServesOn entities.
 *
 * @ingroup dd_committee
 */
interface DdConsultantServesOnInterface extends DdPersonContentEntityInterface {
  /**
   * Gets the DD ConsultantServesOn pid.
   *
   * @return string
   *   Pid of the DD ConsultantServesOn.
   */
  public function getPid();

  /**
   * Sets the DD ConsultantServesOn pid.
   *
   * @param string $pid
   *   The DD ConsultantServesOn pid.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD ConsultantServesOn session_year.
   *
   * @return string
   *   Session Year of the DD ConsultantServesOn.
   */
  public function getSessionYear();

  /**
   * Sets the DD ConsultantServesOn session_year.
   *
   * @param int $session_year
   *   The DD ConsultantServesOn session_year.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD ConsultantServesOn cid.
   *
   * @return string
   *   Cid of the DD ConsultantServesOn.
   */
  public function getCid();

  /**
   * Sets the DD ConsultantServesOn cid.
   *
   * @param string $cid
   *   The DD ConsultantServesOn cid.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setCid($cid);

  /**
   * Gets the DD ConsultantServesOn position.
   *
   * @return string
   *   Position of the DD ConsultantServesOn.
   */
  public function getPosition();

  /**
   * Sets the DD ConsultantServesOn position.
   *
   * @param string $position
   *   The DD ConsultantServesOn position.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setPosition($position);

  /**
   * Gets the DD ConsultantServesOn currentFlag.
   *
   * @return bool
   *   CurrentFlag of the DD ConsultantServesOn.
   */
  public function getCurrentFlag();

  /**
   * Sets the DD ConsultantServesOn currentFlag.
   *
   * @param string $current_flag
   *   The DD ConsultantServesOn currentFlag.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setCurrentFlag($current_flag);

  /**
   * Gets the DD ConsultantServesOn startDate.
   *
   * @return string
   *   StartDate of the DD ConsultantServesOn.
   */
  public function getStartDate();

  /**
   * Sets the DD ConsultantServesOn startDate.
   *
   * @param string $start_date
   *   The DD ConsultantServesOn startDate.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setStartDate($start_date);

  /**
   * Gets the DD ConsultantServesOn endDate.
   *
   * @return string
   *   EndDate of the DD ConsultantServesOn.
   */
  public function getEndDate();

  /**
   * Sets the DD ConsultantServesOn endDate.
   *
   * @param string $end_date
   *   The DD ConsultantServesOn endDate.
   *
   * @return \Drupal\dd_committee\Entity\DdConsultantServesOnInterface
   *   The called DD ConsultantServesOn entity.
   */
  public function setEndDate($end_date);  
}
