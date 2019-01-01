<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Behests entities.
 *
 * @ingroup dd_gift_contribution
 */
interface DdBehestsInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Behests official.
   *
   * @return string
   *   Official of the DD Behests.
   */
  public function getOfficial();

  /**
   * Sets the DD Behests official.
   *
   * @param string $official
   *   The DD Behests official.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setOfficial($official);

  /**
   * Gets the DD Behests datePaid.
   *
   * @return string
   *   DatePaid of the DD Behests.
   */
  public function getDatePaid();

  /**
   * Sets the DD Behests datePaid.
   *
   * @param string $date_paid
   *   The DD Behests datePaid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setDatePaid($date_paid);

  /**
   * Gets the DD Behests SessionYear.
   *
   * @return int
   *   SessionYear of the DD Behests.
   */
  public function getSessionYear();

  /**
   * Sets the DD Behests SessionYear.
   *
   * @param int $session_year
   *   The DD Behests SessionYear.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Behests payor.
   *
   * @return string
   *   Payor of the DD Behests.
   */
  public function getPayor();

  /**
   * Sets the DD Behests payor.
   *
   * @param string $payor
   *   The DD Behests payor.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setPayor($payor);

  /**
   * Gets the DD Behests amount.
   *
   * @return string
   *   Amount of the DD Behests.
   */
  public function getAmount();

  /**
   * Sets the DD Behests amount.
   *
   * @param string $amount
   *   The DD Behests amount.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setAmount($amount);

  /**
   * Gets the DD Behests payee.
   *
   * @return string
   *   Payee of the DD Behests.
   */
  public function getPayee();

  /**
   * Sets the DD Behests payee.
   *
   * @param string $payee
   *   The DD Behests payee.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setPayee($payee);

  /**
   * Gets the DD Behests description.
   *
   * @return string
   *   Description of the DD Behests.
   */
  public function getDescription();

  /**
   * Sets the DD Behests description.
   *
   * @param string $description
   *   The DD Behests description.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setDescription($description);

  /**
   * Gets the DD Behests purpose.
   *
   * @return string
   *   Purpose of the DD Behests.
   */
  public function getPurpose();

  /**
   * Sets the DD Behests purpose.
   *
   * @param string $purpose
   *   The DD Behests purpose.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setPurpose($purpose);

  /**
   * Gets the DD Behests noticeReceived.
   *
   * @return string
   *   NoticeReceived of the DD Behests.
   */
  public function getNoticeReceived();

  /**
   * Sets the DD Behests notice_received.
   *
   * @param string $notice_received
   *   The DD Behests noticeReceived.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdBehestsInterface
   *   The called DD Behests entity.
   */
  public function setNoticeReceived($notice_received);
}
