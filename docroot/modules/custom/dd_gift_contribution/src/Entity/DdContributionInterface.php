<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Contribution entities.
 *
 * @ingroup dd_gift_contribution
 */
interface DdContributionInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD Contribution id.
   *
   * @return string
   *   Id of the DD Contribution.
   */
  public function getId();

  /**
   * Sets the DD Contribution id.
   *
   * @param string $id
   *   The DD Contribution id.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setId($id);

  /**
   * Gets the DD Contribution year.
   *
   * @return string
   *   Year of the DD Contribution.
   */
  public function getYear();

  /**
   * Sets the DD Contribution year.
   *
   * @param string $year
   *   The DD Contribution year.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setYear($year);

  /**
   * Gets the DD Contribution date.
   *
   * @return string
   *   Date of the DD Contribution.
   */
  public function getDate();

  /**
   * Sets the DD Contribution date.
   *
   * @param string $date
   *   The DD Contribution date.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setDate($date);
  /**
   * Gets the DD Contribution house.
   *
   * @return string
   *   House of the DD Contribution.
   */
  public function getHouse();

  /**
   * Sets the DD Contribution house.
   *
   * @param string $house
   *   The DD Contribution house.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD Contribution donorName.
   *
   * @return string
   *   DonorName of the DD Contribution.
   */
  public function getDonorName();

  /**
   * Sets the DD Contribution donorName.
   *
   * @param string $donor_name
   *   The DD Contribution donorName.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setDonorName($donor_name);

  /**
   * Gets the DD Contribution donorOrg.
   *
   * @return string
   *   DonorOrg of the DD Contribution.
   */
  public function getDonorOrg();

  /**
   * Sets the DD Contribution donorOrg.
   *
   * @param string $donor_org
   *   The DD Contribution donorOrg.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setDonorOrg($donor_org);

  /**
   * Gets the DD Contribution amount.
   *
   * @return string
   *   Amount of the DD Contribution.
   */
  public function getAmount();

  /**
   * Sets the DD Contribution amount.
   *
   * @param string $amount
   *   The DD Contribution amount.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setAmount($amount);

  /**
   * Gets the DD Contribution oid.
   *
   * @return string
   *   Oid of the DD Contribution.
   */
  public function getOid();

  /**
   * Sets the DD Contribution oid.
   *
   * @param string $oid
   *   The DD Contribution oid.
   *
   * @return \Drupal\dd_gift_contribution\Entity\DdContributionInterface
   *   The called DD Contribution entity.
   */
  public function setOid($oid);

}
