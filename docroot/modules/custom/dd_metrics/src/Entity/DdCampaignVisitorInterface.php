<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining DD Campaign Visitor entities.
 *
 * @ingroup dd_metrics
 */
interface DdCampaignVisitorInterface extends  ContentEntityInterface, EntityChangedInterface {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD Action Metrics campaign ID.
   *
   * @return int
   *   Campaign ID of the DD Action Metrics.
   */
  public function getCampaignId();

  /**
   * Sets the DD Action Metrics campaign iD.
   *
   * @param int $id
   *   The DD Action Metrics campaign ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdActionMetricsInterface
   *   The called DD Action Metrics entity.
   */
  public function setCampaignId($id);

  /**
   * Gets the DD Campaign Visitor Visitor First Name.
   *
   * @return string
   *   Visitor First Name of the DD Campaign Visitor.
   */
  public function getFirstName();

  /**
   * Sets the DD Campaign Visitor Visitor First Name.
   *
   * @param string $first_name
   *   The DD Campaign Visitor Visitor First Name.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setFirstName($first_name);

  /**
   * Gets the DD Campaign Visitor Visitor Last Name.
   *
   * @return string
   *   Visitor Last Name of the DD Campaign Visitor.
   */
  public function getLastName();

  /**
   * Sets the DD Campaign Visitor Visitor Last Name.
   *
   * @param string $last_name
   *   The DD Campaign Visitor Visitor Last Name.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setLastName($last_name);

  /**
   * Gets the DD Campaign Visitor Visitor Email.
   *
   * @return string
   *   Visitor Email of the DD Campaign Visitor.
   */
  public function getEmail();

  /**
   * Sets the DD Campaign Visitor Visitor Email.
   *
   * @param string $email
   *   The DD Campaign Visitor Visitor Email.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setEmail($email);

  /**
   * Gets the DD Campaign Visitor Visitor Address.
   *
   * @return string
   *   Visitor Address of the DD Campaign Visitor.
   */
  public function getAddress();

  /**
   * Sets the DD Campaign Visitor Visitor Address.
   *
   * @param string $address
   *   The DD Campaign Visitor Visitor Address.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setAddress($address);

  /**
   * Gets the DD Campaign Visitor Visitor City.
   *
   * @return string
   *   Visitor City of the DD Campaign Visitor.
   */
  public function getCity();

  /**
   * Sets the DD Campaign Visitor Visitor City.
   *
   * @param string $city
   *   The DD Campaign Visitor Visitor City.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setCity($city);

  /**
   * Gets the DD Campaign Visitor Visitor State.
   *
   * @return string
   *   Visitor State of the DD Campaign Visitor.
   */
  public function getState();

  /**
   * Sets the DD Campaign Visitor Visitor State.
   *
   * @param string $state
   *   The DD Campaign Visitor Visitor State.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setState($state);

  /**
   * Gets the DD Campaign Visitor Visitor Zip.
   *
   * @return string
   *   Visitor Zip of the DD Campaign Visitor.
   */
  public function getZip();

  /**
   * Sets the DD Campaign Visitor Visitor Zip.
   *
   * @param string $zip
   *   The DD Campaign Visitor Visitor Zip.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setZip($zip);

  /**
   * Gets the DD Campaign Visitor Visitor County.
   *
   * @return string
   *   Visitor County of the DD Campaign Visitor.
   */
  public function getCounty();

  /**
   * Sets the DD Campaign Visitor Visitor County.
   *
   * @param string $county
   *   The DD Campaign Visitor Visitor County.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setCounty($county);

  /**
   * Gets the DD Campaign Visitor Visitor Assembly District.
   *
   * @return int
   *   Visitor Assembly District of the DD Campaign Visitor.
   */
  public function getAssemblyDistrict();

  /**
   * Sets the DD Campaign Visitor Visitor Assembly District.
   *
   * @param int $assembly_district
   *   The DD Campaign Visitor Visitor Assembly District.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setAssemblyDistrict($assembly_district);

  /**
   * Gets the DD Campaign Visitor Visitor Senate District.
   *
   * @return int
   *   Visitor Senate District of the DD Campaign Visitor.
   */
  public function getSenateDistrict();

  /**
   * Sets the DD Campaign Visitor Visitor Senate District.
   *
   * @param int $senate_district
   *   The DD Campaign Visitor Visitor Senate District.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setSenateDistrict($senate_district);

  /**
   * Gets the DD Campaign Visitor Visitor Session ID.
   *
   * @return string
   *   Visitor Session ID District of the DD Campaign Visitor.
   */
  public function getSessionId();

  /**
   * Sets the DD Campaign Visitor Visitor Session ID District.
   *
   * @param string $session_id
   *   The DD Campaign Visitor Visitor Session ID District.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setSessionId($session_id);

  /**
   * Gets the DD Campaign Visitor Visitor User ID.
   *
   * @return string
   *   Visitor User ID District of the DD Campaign Visitor.
   */
  public function getUserId();

  /**
   * Sets the DD Campaign Visitor Visitor User ID District.
   *
   * @param string $user_id
   *   The DD Campaign Visitor Visitor User ID District.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setUserId($user_id);

  /**
   * Gets the DD Campaign Visitor whitelabel ID.
   *
   * @return string
   *   Whitelabel ID of the DD Campaign Visitor.
   */
  public function getWhitelabelId();

  /**
   * Sets the DD Campaign Visitor whitelabel iD.
   *
   * @param string $whitelabel_id
   *   The DD Campaign Visitor whitelabel ID.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setWhitelabelId($whitelabel_id);

  /**
   * Gets the DD Campaign Visitor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Campaign Visitor.
   */
  public function getCreatedTime();

  /**
   * Sets the DD Campaign Visitor creation timestamp.
   *
   * @param int $timestamp
   *   The DD Campaign Visitor creation timestamp.
   *
   * @return \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface
   *   The called DD Campaign Visitor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the DD Campaign Visitor published status indicator.
   *
   * Unpublished DD Campaign Visitor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Campaign Visitor is published.
   */
  public function isPublished();
}
