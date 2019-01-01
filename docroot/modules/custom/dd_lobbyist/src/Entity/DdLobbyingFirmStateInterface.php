<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD LobbyingFirmState entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyingFirmStateInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD LobbyingFirmState FilerId.
   *
   * @return string
   *   FilerId of the DD LobbyingFirmState.
   */
  public function getFilerId();

  /**
   * Sets the DD LobbyingFirmState FilerId.
   *
   * @param string $filer_id
   *   The DD LobbyingFirmState FilerId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setFilerId($filer_id);

  /**
   * Gets the DD LobbyingFirmState rpt_date_ts.
   *
   * @return string
   *   Rpt_date_ts of the DD LobbyingFirmState.
   */
  public function getRptDate();

  /**
   * Sets the DD LobbyingFirmState rpt_date_ts.
   *
   * @param string $rpt_date_ts
   *   The DD LobbyingFirmState rpt_date_ts.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setRptDate($rpt_date_ts);

  /**
   * Gets the DD LobbyingFirmState is_beg_yr.
   *
   * @return int
   *   Is_beg_yr of the DD LobbyingFirmState.
   */
  public function getIsBegYr();

  /**
   * Sets the DD LobbyingFirmState is_beg_yr.
   *
   * @param string $is_beg_yr
   *   The DD LobbyingFirmState is_beg_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setIsBegYr($is_beg_yr);

  /**
   * Gets the DD LobbyingFirmState is_end_yr.
   *
   * @return int
   *   Is_end_yr of the DD LobbyingFirmState.
   */
  public function getIsEndYr();

  /**
   * Sets the DD LobbyingFirmState is_end_yr.
   *
   * @param string $is_end_yr
   *   The DD LobbyingFirmState is_end_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setIsEndYr($is_end_yr);

  /**
   * Gets the DD LobbyingFirmState filer_naml.
   *
   * @return string
   *   State of the DD LobbyingFirmState.
   */
  public function getFilerNaml();

  /**
   * Sets the DD LobbyingFirmState filer_naml.
   *
   * @param string $filer_naml
   *   The DD LobbyingFirmState filer_naml.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyingFirmInterface
   *   The called DD LobbyingFirm entity.
   */
  public function setFilerNaml($filer_naml);

  /**
   * Returns the DD LobbyingFirmState published status indicator.
   *
   * Unpublished DD LobbyingFirmState are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyingFirmState is published.
   */
  public function isPublished();

}
