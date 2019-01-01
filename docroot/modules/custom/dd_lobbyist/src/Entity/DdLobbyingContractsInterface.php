<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD LobbyingContracts entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyingContractsInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD LobbyingContracts FilerId.
   *
   * @return string
   *   FilerId of the DD LobbyingContracts.
   */
  public function getFilerId();

  /**
   * Sets the DD LobbyingContracts FilerId.
   *
   * @param string $filer_id
   *   The DD LobbyingContracts FilerId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setFilerId($filer_id);

  /**
   * Gets the DD LobbyingContracts SenderId.
   *
   * @return string
   *   FilerId of the DD Lobbyist.
   */
  public function getSenderId();

  /**
   * Sets the DD LobbyingContracts SenderId.
   *
   * @param string $sender_id
   *   The DD LobbyingContracts SenderId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setSenderId($sender_id);

  /**
   * Gets the DD LobbyingContracts rpt_date.
   *
   * @return string
   *   Rpt_date_ts of the DD Lobbyist.
   */
  public function getRptDate();

  /**
   * Sets the DD LobbyingContracts rpt_date_ts.
   *
   * @param string $rpt_date_ts
   *   The DD LobbyingContracts rpt_date_ts.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setRptDate($rpt_date_ts);

  /**
   * Gets the DD LobbyingContracts is_beg_yr.
   *
   * @return int
   *   Is_beg_yr of the DD LobbyingContracts.
   */
  public function getIsBegYr();

  /**
   * Sets the DD LobbyingContracts is_beg_yr.
   *
   * @param int $is_beg_yr
   *   The DD LobbyingContracts is_beg_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setIsBegYr($is_beg_yr);

  /**
   * Gets the DD LobbyingContracts is_end_yr.
   *
   * @return int
   *   Is_end_yr of the DD LobbyingContracts.
   */
  public function getIsEndYr();

  /**
   * Sets the DD LobbyingContracts is_end_yr.
   *
   * @param int $is_end_yr
   *   The DD LobbyingContracts is_end_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistInterface
   *   The called DD Lobbyist entity.
   */
  public function setIsEndYr($is_end_yr);

  /**
   * Returns the DD LobbyingContracts published status indicator.
   *
   * Unpublished DD LobbyingContracts are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyingContracts is published.
   */
  public function isPublished();

}
