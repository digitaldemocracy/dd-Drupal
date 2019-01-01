<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Combined Alignments entities.
 *
 * @ingroup dd_person
 */
interface DdCombinedAlignmentsInterface extends DdPersonContentEntityInterface {

  /**
   * Gets the DD Combined Alignments pid.
   *
   * @return int
   *   Pid of the DD Combined Alignments.
   */
  public function getPid();

  /**
   * Sets the DD Combined Alignments pid.
   *
   * @param int $pid
   *   The DD Combined Alignments pid.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setPid($pid);

  /**
 * Gets the DD Combined Alignments oid.
 *
 * @return int
 *   Oid of the DD Combined Alignments.
 */
  public function getOid();

  /**
   * Sets the DD Combined Alignments oid.
   *
   * @param int $oid
   *   The DD Combined Alignments oid.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD Combined Alignments house.
   *
   * @return string
   *   House of the DD Combined Alignments.
   */
  public function getHouse();

  /**
   * Sets the DD Combined Alignments house.
   *
   * @param string $house
   *   The DD Combined Alignments house.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD Combined Alignments party.
   *
   * @return string
   *   Party of the DD Combined Alignments.
   */
  public function getParty();

  /**
   * Sets the DD Combined Alignments party.
   *
   * @param string $party
   *   The DD Combined Alignments party.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setParty($party);

  /**
   * Gets the DD Combined Alignments score.
   *
   * @return float
   *   Score of the DD Combined Alignments.
   */
  public function getScore();

  /**
   * Sets the DD Combined Alignments score.
   *
   * @param float $score
   *   The DD Combined Alignments score.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setScore($score);

  /**
   * Gets the DD Combined Alignments positions registered.
   *
   * @return int
   *   Positions registered of the DD Combined Alignments.
   */
  public function getPositionsRegistered();

  /**
   * Sets the DD Combined Alignments positions registered.
   *
   * @param int $positions
   *   The DD Combined Alignments positions registered.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setPositionsRegistered($positions);

  /**
   * Gets the DD Combined Alignments votes in agreement.
   *
   * @return int
   *   Votes in agreement of the DD Combined Alignments.
   */
  public function getVotesInAgreement();

  /**
   * Sets the DD Combined Alignments votes in agreement.
   *
   * @param int $votes
   *   The DD Combined Alignments votes in agreement.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setVotesInAgreement($votes);

  /**
   * Gets the DD Combined Alignments votes in disagreement.
   *
   * @return int
   *   Votes in disagreement of the DD Combined Alignments.
   */
  public function getVotesInDisagreement();

  /**
   * Sets the DD Combined Alignments votes in disagreement.
   *
   * @param int $votes
   *   The DD Combined Alignments votes in disagreement.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setVotesInDisagreement($votes);

  /**
   * Gets the DD Combined Alignments pid house party.
   *
   * @return string
   *   PID House/Party setting of the DD Combined Alignments.
   */
  public function getPidHouseParty();

  /**
   * Sets the DD Combined Alignments PID House/Party setting.
   *
   * @param string $pid_house_party
   *   The DD Combined Alignments PID House/Party setting.
   *
   * @return \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface
   *   The called DD Combined Alignments entity.
   */
  public function setPidHouseParty($pid_house_party);

}
