<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD LobbyistEmployer entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyistEmployerInterface extends ContentEntityInterface {

  /**
   * Gets the DD LobbyistEmployer FilerId.
   *
   * @return string
   *   FilerId of the DD LobbyistEmployer.
   */
  public function getFilerId();

  /**
   * Sets the DD LobbyistEmployer FilerId.
   *
   * @param string $filer_id
   *   The DD LobbyistEmployer FilerId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmployerInterface
   *   The called DD LobbyistEmployer entity.
   */
  public function setFilerId($filer_id);

  /**
   * Gets the DD LobbyistEmployer oid.
   *
   * @return int
   *   Oid of the DD LobbyistEmployer.
   */
  public function getOid();

  /**
   * Sets the DD LobbyistEmployer oid.
   *
   * @param int $oid
   *   The DD LobbyistEmployer oid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmployerInterface
   *   The called DD LobbyistEmployer entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD LobbyistEmployer coalition.
   *
   * @return boolean
   *   Coalition of the DD LobbyistEmployer.
   */
  public function getCoalition();

  /**
   * Sets the DD LobbyistEmployer coalition.
   *
   * @param boolean $coalition
   *   The DD LobbyistEmployer coalition.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmployerInterface
   *   The called DD LobbyistEmployer entity.
   */
  public function setCoalition($coalition);

  /**
   * Returns the DD LobbyistEmployer published status indicator.
   *
   * Unpublished DD LobbyistEmployer are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyistEmployer is published.
   */
  public function isPublished();

}
