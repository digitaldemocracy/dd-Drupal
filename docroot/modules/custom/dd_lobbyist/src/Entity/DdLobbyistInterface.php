<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Lobbyist entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyistInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD Lobbyist pid.
   *
   * @return int
   *   Pid of the DD Lobbyist.
   */
  public function getPid();

  /**
   * Sets the DD Lobbyist pid.
   *
   * @param int $pid
   *   The DD Lobbyist pid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Lobbyist FilerId.
   *
   * @return string
   *   FilerId of the DD Lobbyist.
   */
  public function getFilerId();

  /**
   * Sets the DD Lobbyist FilerId.
   *
   * @param string $filer_id
   *   The DD Lobbyist FilerId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setFilerId($filer_id);

  /**
   * Returns the DD Lobbyist published status indicator.
   *
   * Unpublished DD Lobbyist are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Lobbyist is published.
   */
  public function isPublished();

}
