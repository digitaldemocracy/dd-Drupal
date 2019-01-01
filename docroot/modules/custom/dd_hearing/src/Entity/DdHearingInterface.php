<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Hearing entities.
 *
 * @ingroup dd_hearing
 */
interface DdHearingInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Hearing date.
   *
   * @return string
   *   Date of the DD Hearing.
   */
  public function getDate();

  /**
   * Sets the DD Hearing date.
   *
   * @param string $date
   *   The DD Hearing date.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingInterface
   *   The called DD Hearing entity.
   */
  public function setDate($date);

  /**
   * Gets the DD Hearing sessionYear.
   *
   * @return string
   *   SessionYear of the DD Hearing.
   */
  public function getSessionYear();

  /**
   * Sets the DD Hearing sessionYear.
   *
   * @param string $session_year
   *   The DD Hearing sessionYear.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingInterface
   *   The called DD Hearing entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Hearing CIDs.
   *
   * @return string
   *   Cids of the DD Hearing.
   */
  public function getCids();

  /**
   * Sets the DD Hearing CIDs.
   *
   * @param string $cids
   *   The DD Hearing CIDs.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingInterface
   *   The called DD Hearing entity.
   */
  public function setCids($cids);

  /**
   * Gets the DD Hearing Committee Name IDs.
   *
   * @return string
   *   Committee Name IDs of the DD Hearing.
   */
  public function getCommitteeNameIds();

  /**
   * Sets the DD Hearing Committee Name IDs.
   *
   * @param string $cn_ids
   *   The DD Hearing Committee Name IDs.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingInterface
   *   The called DD Hearing entity.
   */
  public function setCommitteeNameIds($cn_ids);

  /**
   * Gets the DD Hearing Dids.
   *
   * @return string
   *   Dids of the DD Hearing.
   */
  public function getDids();

  /**
   * Sets the DD Hearing Dids.
   *
   * @param string $dids
   *   The DD Hearing Dids.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingInterface
   *   The called DD Hearing entity.
   */
  public function setDids($dids);

  /**
   * Returns the DD Hearing published status indicator.
   *
   * Unpublished DD Hearing are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Hearing is published.
   */
  public function isPublished();

}
