<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD LobbyistDirectEmployment entities.
 *
 * @ingroup dd_lobbyist
 */
interface DdLobbyistDirectEmploymentInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD LobbyistDirectEmployment Pid.
   *
   * @return int
   *   Pid of the DD LobbyistDirectEmployment.
   */
  public function getPid();

  /**
   * Sets the DD LobbyistDirectEmployment Pid.
   *
   * @param int $pid
   *   The DD LobbyistDirectEmployment Pid.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistDirectEmploymentInterface
   *   The called DD LobbyistDirectEmployment entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD LobbyistDirectEmployment SenderId.
   *
   * @return string
   *   SenderId of the DD LobbyistDirectEmployment.
   */
  public function getSenderId();

  /**
   * Sets the DD LobbyistDirectEmployment SenderId.
   *
   * @param string $sender_id
   *   The DD LobbyistDirectEmployment SenderId.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistDirectEmploymentInterface
   *   The called DD LobbyistDirectEmployment entity.
   */
  public function setSenderId($sender_id);

  /**
   * Gets the DD LobbyistDirectEmployment rpt_date_ts.
   *
   * @return string
   *   Rpt_date_ts of the DD LobbyistDirectEmployment.
   */
  public function getRptDate();

  /**
   * Sets the DD LobbyistDirectEmployment rpt_date_ts.
   *
   * @param string $rpt_date_ts
   *   The DD LobbyistDirectEmployment rpt_date_ts.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistDirectEmploymentInterface
   *   The called DD LobbyistDirectEmployment entity.
   */
  public function setRptDate($rpt_date_ts);

  /**
   * Gets the DD LobbyistDirectEmployment is_beg_yr.
   *
   * @return int
   *   Is_beg_yr of the DD LobbyistDirectEmployment.
   */
  public function getIsBegYr();

  /**
   * Sets the DD LobbyistDirectEmployment is_beg_yr.
   *
   * @param int $is_beg_yr
   *   The DD LobbyistDirectEmployment is_beg_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistDirectEmploymentInterface
   *   The called DD LobbyistDirectEmployment entity.
   */
  public function setIsBegYr($is_beg_yr);

  /**
   * Gets the DD LobbyistDirectEmployment is_end_yr.
   *
   * @return int
   *   Is_end_yr of the DD LobbyistDirectEmployment.
   */
  public function getIsEndYr();

  /**
   * Sets the DD LobbyistDirectEmployment is_end_yr.
   *
   * @param int $is_end_yr
   *   The DD LobbyistDirectEmployment is_end_yr.
   *
   * @return \Drupal\dd_lobbyist\Entity\DdLobbyistDirectEmploymentInterface
   *   The called DD LobbyistDirectEmployment entity.
   */
  public function setIsEndYr($is_end_yr);

  /**
   * Returns the DD LobbyistDirectEmployment published status indicator.
   *
   * Unpublished DD LobbyistDirectEmployment only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD LobbyistDirectEmployment is published.
   */
  public function isPublished();

}
