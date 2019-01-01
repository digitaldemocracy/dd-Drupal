<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD HearingAgenda entities.
 *
 * @ingroup dd_hearingAgenda
 */
interface DdHearingAgendaInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD HearingAgenda hid.
   *
   * @return int
   *   HID of the DD HearingAgenda.
   */
  public function getHid();

  /**
   * Sets the DD HearingAgenda hid.
   *
   * @param int $hid
   *   The DD HearingAgenda hid.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingAgendaInterface
   *   The called DD HearingAgenda entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD HearingAgenda bid.
   *
   * @return string
   *   BID of the DD HearingAgenda.
   */
  public function getBid();

  /**
   * Sets the DD HearingAgenda bid.
   *
   * @param string $bid
   *   The DD HearingAgenda bid.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingAgendaInterface
   *   The called DD HearingAgenda entity.
   */
  public function setBid($bid);


  /**
   * Gets the DD HearingAgenda currentFlag.
   *
   * @return bool
   *   CurrentFlag of the DD HearingAgenda.
   */
  public function getCurrentFlag();

  /**
   * Sets the DD HearingAgenda currentFlag.
   *
   * @param bool $current_flag
   *   The DD HearingAgenda currentFlag.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingAgendaInterface
   *   The called DD HearingAgenda entity.
   */
  public function setCurrentFlag($current_flag);

  /**
   * Gets the DD HearingAgenda dateCreated.
   *
   * @return int
   *   DateCreated of the DD HearingAgenda.
   */
  public function getDateCreated();

  /**
   * Sets the DD HearingAgenda dateCreated.
   *
   * @param int $date_created
   *   The DD HearingAgenda dateCreated.
   *
   * @return \Drupal\dd_hearing\Entity\DdHearingAgendaInterface
   *   The called DD HearingAgenda entity.
   */
  public function setDateCreated($date_created);

  /**
   * Returns the DD HearingAgenda published status indicator.
   *
   * Unpublished DD HearingAgenda are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD HearingAgenda is published.
   */
  public function isPublished();

}
