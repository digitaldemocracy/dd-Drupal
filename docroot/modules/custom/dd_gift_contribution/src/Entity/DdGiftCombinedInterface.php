<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD GiftCombined entities.
 *
 * @ingroup dd_gift_contribution
 */
interface DdGiftCombinedInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD GiftCombined recipientPid.
   *
   * @return string
   *   RecipientPid of the DD GiftCombined.
   */
  public function getRecipientPid();

  /**
   * Sets the DD GiftCombined recipientPid.
   *
   * @param string $recipient_pid
   *   The DD GiftCombined recipientPid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setRecipientPid($recipient_pid);

  /**
   * Gets the DD GiftCombined legislatorPid.
   *
   * @return string
   *   LegislatorPid of the DD GiftCombined.
   */
  public function getLegislatorPid();

  /**
   * Sets the DD GiftCombined legislatorPid.
   *
   * @param string $legislator_pid
   *   The DD GiftCombined legislatorPid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setLegislatorPid($legislator_pid);

  /**
   * Gets the DD GiftCombined giftDate.
   *
   * @return string
   *   GiftDate of the DD GiftCombined.
   */
  public function getGiftDate();

  /**
   * Sets the DD GiftCombined giftDate.
   *
   * @param string $gift_date
   *   The DD GiftCombined giftDate.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setGiftDate($gift_date);

  /**
   * Gets the DD GiftCombined SessionYear.
   *
   * @return int
   *   SessionYear of the DD Gift.
   */
  public function getSessionYear();

  /**
   * Sets the DD Gift SessionYear.
   *
   * @param int $session_year
   *   The DD Gift SessionYear.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftGiftCombined entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD GiftCombined year.
   *
   * @return string
   *   Year of the DD GiftCombined.
   */
  public function getYear();

  /**
   * Sets the DD GiftCombined year.
   *
   * @param string $year
   *   The DD GiftCombined year.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setYear($year);


  /**
   * Gets the DD GiftCombined description.
   *
   * @return string
   *   Description of the DD GiftCombined.
   */
  public function getDescription();

  /**
   * Sets the DD GiftCombined description.
   *
   * @param string $description
   *   The DD GiftCombined description.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setDescription($description);

  /**
   * Gets the DD GiftCombined giftValue.
   *
   * @return string
   *   GiftValue of the DD GiftCombined.
   */
  public function getGiftValue();

  /**
   * Sets the DD GiftCombined giftValue.
   *
   * @param string $gift_value
   *   The DD GiftCombined giftValue.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setGiftValue($gift_value);

  /**
   * Gets the DD GiftCombined agencyName.
   *
   * @return string
   *   AgencyName of the DD GiftCombined.
   */
  public function getAgencyName();

  /**
   * Sets the DD GiftCombined agencyName.
   *
   * @param string $agency_name
   *   The DD GiftCombined agencyName.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setAgencyName($agency_name);

  /**
   * Gets the DD GiftCombined sourceName.
   *
   * @return string
   *   SourceName of the DD GiftCombined.
   */
  public function getSourceName();

  /**
   * Sets the DD GiftCombined sourceName.
   *
   * @param string $source_name
   *   The DD GiftCombined sourceName.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSourceName($source_name);

  /**
   * Gets the DD GiftCombined sourceBusiness.
   *
   * @return string
   *   SourceBusiness of the DD GiftCombined.
   */
  public function getSourceBusiness();

  /**
   * Sets the DD GiftCombined sourceBusiness.
   *
   * @param string $source_business
   *   The DD GiftCombined sourceBusiness.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSourceBusiness($source_business);

  /**
   * Gets the DD GiftCombined sourceCity.
   *
   * @return string
   *   SourceCity of the DD GiftCombined.
   */
  public function getSourceCity();

  /**
   * Sets the DD GiftCombined sourceCity.
   *
   * @param string $sourceCity
   *   The DD GiftCombined sourceCity.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSourceCity($sourceCity);

  /**
   * Gets the DD GiftCombined sourceState.
   *
   * @return string
   *   SourceState of the DD GiftCombined.
   */
  public function getSourceState();

  /**
   * Sets the DD GiftCombined sourceState.
   *
   * @param string $source_state
   *   The DD GiftCombined sourceState.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSourceState($source_state);

  /**
   * Gets the DD GiftCombined imageUrl.
   *
   * @return string
   *   ImageUrl of the DD GiftCombined.
   */
  public function getImageUrl();

  /**
   * Sets the DD GiftCombined imageUrl.
   *
   * @param string $image_url
   *   The DD GiftCombined imageUrl.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setImageUrl($image_url);

  /**
   * Gets the DD GiftCombined oid.
   *
   * @return string
   *   Oid of the DD GiftCombined.
   */
  public function getOid();

  /**
   * Sets the DD GiftCombined oid.
   *
   * @param string $oid
   *   The DD GiftCombined oid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD GiftCombined activity.
   *
   * @return string
   *   Activity of the DD GiftCombined.
   */
  public function getActivity();

  /**
   * Sets the DD GiftCombined activity.
   *
   * @param string $activity
   *   The DD GiftCombined activity.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setActivity($activity);

  /**
   * Gets the DD GiftCombined position.
   *
   * @return string
   *   Position of the DD GiftCombined.
   */
  public function getPosition();

  /**
   * Sets the DD GiftCombined position.
   *
   * @param string $position
   *   The DD GiftCombined position.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setPosition($position);

  /**
   * Gets the DD GiftCombined schedule.
   *
   * @return string
   *   Schedule of the DD GiftCombined.
   */
  public function getSchedule();

  /**
   * Sets the DD GiftCombined schedule.
   *
   * @param string $schedule
   *   The DD GiftCombined schedule.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSchedule($schedule);

  /**
   * Gets the DD GiftCombined jurisdiction.
   *
   * @return string
   *   Jurisdiction of the DD GiftCombined.
   */
  public function getJurisdiction();

  /**
   * Sets the DD GiftCombined jurisdiction.
   *
   * @param string $jurisdiction
   *   The DD GiftCombined jurisdiction.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setJurisdiction($jurisdiction);
  /**
   * Gets the DD GiftCombined districtNumber.
   *
   * @return string
   *   DistrictNumber of the DD GiftCombined.
   */
  public function getDistrictNumber();

  /**
   * Sets the DD GiftCombined districtNumber.
   *
   * @param string $district_number
   *   The DD GiftCombined districtNumber.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setDistrictNumber($district_number);

  /**
   * Gets the DD GiftCombined reimbursed.
   *
   * @return string
   *   Reimbursed of the DD GiftCombined.
   */
  public function getReimbursed();

  /**
   * Sets the DD GiftCombined reimbursed.
   *
   * @param string $reimbursed
   *   The DD GiftCombined reimbursed.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setReimbursed($reimbursed);

  /**
   * Gets the DD GiftCombined giftIncomeFlag.
   *
   * @return string
   *   GiftIncomeFlag of the DD GiftCombined.
   */
  public function getGiftIncomeFlag();

  /**
   * Sets the DD GiftCombined giftIncomeFlag.
   *
   * @param string $gift_income_flag
   *   The DD GiftCombined giftIncomeFlag.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setGiftIncomeFlag($gift_income_flag);

  /**
   * Gets the DD GiftCombined speechFlag.
   *
   * @return string
   *   SpeechFlag of the DD GiftCombined.
   */
  public function getSpeechFlag();

  /**
   * Sets the DD GiftCombined speechFlag.
   *
   * @param string $speech_flag
   *   The DD GiftCombined speechFlag.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSpeechFlag($speech_flag);

  /**
   * Gets the DD GiftCombined speechOrPanel.
   *
   * @return string
   *   SpeechOrPanel of the DD GiftCombined.
   */
  public function getSpeechOrPanel();

  /**
   * Sets the DD GiftCombined speechOrPanel.
   *
   * @param string $speech_or_panel
   *   The DD GiftCombined speechOrPanel.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdGiftCombinedInterface
   *   The called DD GiftCombined entity.
   */
  public function setSpeechOrPanel($speech_or_panel);
}
