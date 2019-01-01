<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD LobbyingFirm entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyingFirmInterface extends ContentEntityInterface {

  /**
   * Gets the DD LobbyingFirm filer_naml.
   *
   * @return string
   *   Filer_naml of the DD LobbyingFirm.
   */
  public function getFilerNaml();

  /**
   * Sets the DD LobbyingFirm filer_naml.
   *
   * @param string $filer_naml
   *   The DD LobbyingFirm filer_naml.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setFilerNaml($filer_naml);

  /**
   * Returns the DD LobbyingFirm published status indicator.
   *
   * Unpublished DD LobbyingFirm are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyingFirm is published.
   */
  public function isPublished();

}
