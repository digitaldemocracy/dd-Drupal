<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD LobbyistRepresentation entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyistRepresentationInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD LobbyistRepresentation pid.
   *
   * @return int
   *   Pid of the DD LobbyistRepresentation.
   */
  public function getPid();

  /**
   * Sets the DD LobbyistRepresentation pid.
   *
   * @param int $pid
   *   The DD LobbyistRepresentation pid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationInterface
   *   The called DD LobbyistRepresentation entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD LobbyistRepresentation oid.
   *
   * @return int
   *   Oid of the DD LobbyistRepresentation.
   */
  public function getOid();

  /**
   * Sets the DD LobbyistRepresentation oid.
   *
   * @param int $oid
   *   The DD LobbyistRepresentation oid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationInterface
   *   The called DD LobbyistRepresentation entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD LobbyistRepresentation hearingDate.
   *
   * @return string
   *   HearingDate of the DD LobbyistRepresentation.
   */
  public function getHearingDate();

  /**
   * Sets the DD LobbyistRepresentation hearingDate.
   *
   * @param string $hearing_date
   *   The DD LobbyistRepresentation hearingDate.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationInterface
   *   The called DD LobbyistRepresentation entity.
   */
  public function setHearingDate($hearing_date);

  /**
   * Gets the DD LobbyistRepresentation hid.
   *
   * @return int
   *   Hid of the DD LobbyistRepresentation.
   */
  public function getHid();

  /**
   * Sets the DD LobbyistRepresentation hid.
   *
   * @param int $hid
   *   The DD LobbyistRepresentation hid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationInterface
   *   The called DD LobbyistRepresentation entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD LobbyistRepresentation did.
   *
   * @return int
   *   Did of the DD LobbyistRepresentation.
   */
  public function getDid();

  /**
   * Sets the DD LobbyistRepresentation did.
   *
   * @param int $did
   *   The DD LobbyistRepresentation did.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationInterface
   *   The called DD LobbyistRepresentation entity.
   */
  public function setDid($did);

  /**
   * Returns the DD LobbyistRepresentation published status indicator.
   *
   * Unpublished DD LobbyistRepresentation are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyistRepresentation is published.
   */
  public function isPublished();

}
