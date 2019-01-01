<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Gift entities.
 *
 * @ingroup dd_gift_contribution
 */
interface DdGiftInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Gift schedule.
   *
   * @return string
   *   Schedule of the DD Gift.
   */
  public function getSchedule();

  /**
   * Sets the DD Gift schedule.
   *
   * @param string $schedule
   *   The DD Gift schedule.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setSchedule($schedule);

  /**
   * Gets the DD Gift sourceName.
   *
   * @return string
   *   SourceName of the DD Gift.
   */
  public function getSourceName();

  /**
   * Sets the DD Gift sourceName.
   *
   * @param string $source_name
   *   The DD Gift sourceName.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setSourceName($source_name);

  /**
   * Gets the DD Gift activity.
   *
   * @return string
   *   Activity of the DD Gift.
   */
  public function getActivity();

  /**
   * Sets the DD Gift activity.
   *
   * @param string $activity
   *   The DD Gift activity.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setActivity($activity);

  /**
   * Gets the DD Gift city.
   *
   * @return string
   *   City of the DD Gift.
   */
  public function getCity();

  /**
   * Sets the DD Gift city.
   *
   * @param string $city
   *   The DD Gift city.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setCity($city);

  /**
   * Gets the DD Gift cityState.
   *
   * @return string
   *   CityState of the DD Gift.
   */
  public function getCityState();

  /**
   * Sets the DD Gift cityState.
   *
   * @param string $city_state
   *   The DD Gift cityState.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setCityState($city_state);

  /**
   * Gets the DD Gift value.
   *
   * @return string
   *   Value of the DD Gift.
   */
  public function getValue();

  /**
   * Sets the DD Gift value.
   *
   * @param string $value
   *   The DD Gift value.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setValue($value);

  /**
   * Gets the DD Gift giftDate.
   *
   * @return string
   *   GiftDate of the DD Gift.
   */
  public function getGiftDate();

  /**
   * Sets the DD Gift giftDate.
   *
   * @param string $gift_date
   *   The DD Gift giftDate.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setGiftDate($gift_date);

  /**
   * Gets the DD Gift SessionYear.
   *
   * @return int
   *   SessionYear of the DD Gift.
   */
  public function getSessionYear();

  /**
   * Sets the DD Gift SessionYear.
   *
   * @param int $session_year
   *   The DD Gift sessionYear.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Gift reimbursed.
   *
   * @return string
   *   Reimbursed of the DD Gift.
   */
  public function getReimbursed();

  /**
   * Sets the DD Gift reimbursed.
   *
   * @param string $reimbursed
   *   The DD Gift reimbursed.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setReimbursed($reimbursed);

  /**
   * Gets the DD Gift giftIncomeFlag.
   *
   * @return string
   *   GiftIncomeFlag of the DD Gift.
   */
  public function getGiftIncomeFlag();

  /**
   * Sets the DD Gift giftIncomeFlag.
   *
   * @param string $gift_income_flag
   *   The DD Gift giftIncomeFlag.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setGiftIncomeFlag($gift_income_flag);

  /**
   * Gets the DD Gift speechFlag.
   *
   * @return string
   *   SpeechFlag of the DD Gift.
   */
  public function getSpeechFlag();

  /**
   * Sets the DD Gift speechFlag.
   *
   * @param string $speech_flag
   *   The DD Gift speechFlag.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setSpeechFlag($speech_flag);

  /**
   * Gets the DD Gift description.
   *
   * @return string
   *   Description of the DD Gift.
   */
  public function getDescription();

  /**
   * Sets the DD Gift description.
   *
   * @param string $description
   *   The DD Gift description.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setDescription($description);

  /**
   * Gets the DD Gift oid.
   *
   * @return string
   *   Oid of the DD Gift.
   */
  public function getOid();

  /**
   * Sets the DD Gift oid.
   *
   * @param string $oid
   *   The DD Gift oid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftInterface
   *   The called DD Gift entity.
   */
  public function setOid($oid);

}
