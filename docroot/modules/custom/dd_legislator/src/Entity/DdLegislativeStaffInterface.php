<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Legislative Staff entities.
 *
 * @ingroup dd_legislator
 */
interface DdLegislativeStaffInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Legislative Staff flag.
   *
   * @return string
   *   Flag of the DD Legislative Staff.
   */
  public function getFlag();

  /**
   * Sets the DD Legislative Staff flag.
   *
   * @param string $flag
   *   The DD Legislative Staff flag.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislativeStaffInterface
   *   The called DD Legislative Staff entity.
   */
  public function setFlag($flag);

  /**
   * Gets the DD Legislative Staff legislator.
   *
   * @return string
   *   Legislator of the DD Legislative Staff.
   */
  public function getLegislator();

  /**
   * Sets the DD Legislative Staff legislator.
   *
   * @param string $legislator
   *   The DD Legislative Staff legislator.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislativeStaffInterface
   *   The called DD Legislative Staff entity.
   */
  public function setLegislator($legislator);

  /**
   * Gets the DD Legislative Staff committee.
   *
   * @return string
   *   Committee of the DD Legislative Staff.
   */
  public function getCommittee();

  /**
   * Sets the DD Legislative Staff committee.
   *
   * @param string $committee
   *   The DD Legislative Staff committee.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislativeStaffInterface
   *   The called DD Legislative Staff entity.
   */
  public function setCommittee($committee);

}
