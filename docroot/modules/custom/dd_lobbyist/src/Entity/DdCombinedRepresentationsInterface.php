<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD CombinedRepresentations entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdCombinedRepresentationsInterface extends DdBaseStateFieldInterface  {

  /**
   * Gets the DD CombinedRepresentations Pid.
   *
   * @return int
   *   Pid of the DD CombinedRepresentations.
   */
  public function getPid();

  /**
   * Sets the DD CombinedRepresentations Pid.
   *
   * @param int $pid
   *   The DD CombinedRepresentations Pid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsInterface
   *   The called DD CombinedRepresentations entity.
   */
  public function setPid($pid);


  /**
   * Gets the DD CombinedRepresentations Hid.
   *
   * @return int
   *   Hid of the DD CombinedRepresentations.
   */
  public function getHid();

  /**
   * Sets the DD CombinedRepresentations Hid.
   *
   * @param int $hid
   *   The DD CombinedRepresentations Hid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsInterface
   *   The called DD CombinedRepresentations entity.
   */
  public function setHid($hid);


  /**
   * Gets the DD CombinedRepresentations Did.
   *
   * @return int
   *   Did of the DD CombinedRepresentations.
   */
  public function getDid();

  /**
   * Sets the DD CombinedRepresentations Did.
   *
   * @param int $did
   *   The DD CombinedRepresentations Did.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsInterface
   *   The called DD CombinedRepresentations entity.
   */
  public function setDid($did);


  /**
   * Gets the DD CombinedRepresentations Oid.
   *
   * @return int
   *   Oid of the DD CombinedRepresentations.
   */
  public function getOid();

  /**
   * Sets the DD CombinedRepresentations Oid.
   *
   * @param int $oid
   *   The DD CombinedRepresentations Oid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsInterface
   *   The called DD CombinedRepresentations entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD CombinedRepresentations Year.
   *
   * @return int
   *   Year of the DD CombinedRepresentations.
   */
  public function getYear();

  /**
   * Sets the DD CombinedRepresentations Year.
   *
   * @param int $year
   *   The DD CombinedRepresentations Year.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsInterface
   *   The called DD CombinedRepresentations entity.
   */
  public function setYear($year);

  /**
   * Returns the DD CombinedRepresentations published status indicator.
   *
   * Unpublished DD CombinedRepresentations only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD CombinedRepresentations is published.
   */
  public function isPublished();

}
