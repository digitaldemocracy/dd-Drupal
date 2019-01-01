<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD LobbyistEmployment entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyistEmploymentInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD LobbyistEmployment Pid.
   *
   * @return int
   *   Pid of the DD LobbyistEmployment.
   */
  public function getPid();

  /**
   * Sets the DD LobbyistEmployment Pid.
   *
   * @param int $pid
   *   The DD LobbyistEmployment Pid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentInterface
   *   The called DD LobbyistEmployment entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD LobbyistEmployment SenderId.
   *
   * @return string
   *   SenderId of the DD LobbyistEmployment.
   */
  public function getSenderId();

  /**
   * Sets the DD LobbyistEmployment SenderId.
   *
   * @param string $sender_id
   *   The DD LobbyistEmployment SenderId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentInterface
   *   The called DD LobbyistEmployment entity.
   */
  public function setSenderId($sender_id);

  /**
   * Gets the DD LobbyistEmployment rpt_date_ts.
   *
   * @return string
   *   Rpt_date_ts of the DD LobbyistEmployment.
   */
  public function getRptDate();

  /**
   * Sets the DD LobbyistEmployment rpt_date_ts.
   *
   * @param string $rpt_date_ts
   *   The DD LobbyistEmployment rpt_date_ts.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentInterface
   *   The called DD LobbyistEmployment entity.
   */
  public function setRptDate($rpt_date_ts);

  /**
   * Gets the DD LobbyistEmployment is_beg_yr.
   *
   * @return int
   *   Is_beg_yr of the DD LobbyistEmployment.
   */
  public function getIsBegYr();

  /**
   * Sets the DD LobbyistEmployment is_beg_yr.
   *
   * @param int $is_beg_yr
   *   The DD LobbyistEmployment is_beg_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentInterface
   *   The called DD LobbyistEmployment entity.
   */
  public function setIsBegYr($is_beg_yr);

  /**
   * Gets the DD LobbyistEmployment is_end_yr.
   *
   * @return int
   *   Is_end_yr of the DD LobbyistEmployment.
   */
  public function getIsEndYr();

  /**
   * Sets the DD LobbyistEmployment is_end_yr.
   *
   * @param int $is_end_yr
   *   The DD LobbyistEmployment is_end_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentInterface
   *   The called DD LobbyistEmployment entity.
   */
  public function setIsEndYr($is_end_yr);

  /**
   * Returns the DD LobbyistEmployment published status indicator.
   *
   * Unpublished DD LobbyistEmployment are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyistEmployment is published.
   */
  public function isPublished();

}
