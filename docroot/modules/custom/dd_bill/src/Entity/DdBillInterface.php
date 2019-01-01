<?php

namespace Drupal\dd_bill\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Bill entities.
 *
 * @ingroup dd_bill
 */
interface DdBillInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Bill type.
   *
   * @return string
   *   Type of the DD Bill.
   */
  public function getType();

  /**
   * Sets the DD Bill type.
   *
   * @param string $type
   *   The DD Bill type.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setType($type);

  /**
   * Gets the DD Bill number.
   *
   * @return string
   *   Number of the DD Bill.
   */
  public function getNumber();

  /**
   * Sets the DD Bill number.
   *
   * @param string $number
   *   The DD Bill number.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setNumber($number);

  /**
   * Gets the DD Bill billState.
   *
   * @return string
   *   BillState of the DD Bill.
   */
  public function getBillState();

  /**
   * Sets the DD Bill billState.
   *
   * @param string $bill_state
   *   The DD Bill billState.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setBillState($bill_state);

  /**
   * Gets the DD Bill status.
   *
   * @return string
   *   Status of the DD Bill.
   */
  public function getStatus();

  /**
   * Sets the DD Bill status.
   *
   * @param string $status
   *   The DD Bill status.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setStatus($status);

  /**
   * Gets the DD Bill house.
   *
   * @return string
   *   House of the DD Bill.
   */
  public function getHouse();

  /**
   * Sets the DD Bill house.
   *
   * @param string $house
   *   The DD Bill house.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD Bill session.
   *
   * @return string
   *   Session of the DD Bill.
   */
  public function getSession();

  /**
   * Sets the DD Bill session.
   *
   * @param string $session
   *   The DD Bill session.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setSession($session);

  /**
   * Gets the DD Bill sessionYear.
   *
   * @return string
   *   SessionYear of the DD Bill.
   */
  public function getSessionYear();

  /**
   * Sets the DD Bill sessionYear.
   *
   * @param string $session_year
   *   The DD Bill sessionYear.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Bill billVersionCurrentDrId.
   *
   * @return int
   *   billVersionCurrentDrId of the DD Bill.
   */
  public function getBillVersionCurrentDrId();

  /**
   * Sets the DD Bill billVersionCurrentDrId.
   *
   * @param int $dr_id
   *   The DD Bill billVersionCurrentDrId.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setBillVersionCurrentDrId($dr_id);

  /**
   * Gets the DD Bill primaryAuthorPid.
   *
   * @return int
   *   primaryAuthorPid of the DD Bill.
   */
  public function getPrimaryAuthorPid();

  /**
   * Sets the DD Bill primaryAuthorPid.
   *
   * @param int $pid
   *   The DD Bill primaryAuthorPid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setPrimaryAuthorPid($pid);
  /**
   * Gets the DD Bill type + number.
   *
   * @return string
   *   Type + number of the DD Bill (AB263).
   */
  public function getTypeNumber();

  /**
   * Sets the DD Bill type + number.
   *
   * @param string $type_number
   *   The DD Bill type + number.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD Bill entity.
   */
  public function setTypeNumber($type_number);

}
