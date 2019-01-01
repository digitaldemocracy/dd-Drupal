<?php

namespace Drupal\dd_committee\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Committee entities.
 *
 * @ingroup dd_committee
 */
interface DdCommitteeInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Committee house.
   *
   * @return string
   *   House of the DD Committee.
   */
  public function getHouse();

  /**
   * Sets the DD Committee house.
   *
   * @param string $house
   *   The DD Committee house.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setHouse($house);

  /**
   * Gets the DD Committee name.
   *
   * @return string
   *   Name of the DD Committee.
   */
  public function getName();

  /**
   * Sets the DD Committee name.
   *
   * @param string $name
   *   The DD Committee name.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setName($name);

  /**
   * Gets the DD Committee type.
   *
   * @return string
   *   Type of the DD Committee.
   */
  public function getType();

  /**
   * Sets the DD Committee type.
   *
   * @param string $type
   *   The DD Committee type.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setType($type);

  /**
   * Gets the DD Committee creation timestamp.
   *
   * @return int
   *   Creation timestamp of the DD Committee.
   */

  /**
   * Gets the DD Committee shortName.
   *
   * @return string
   *   ShortName of the DD Committee.
   */
  public function getShortName();

  /**
   * Sets the DD Committee shortName.
   *
   * @param string $short_name
   *   The DD Committee shortName.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setShortName($short_name);

  /**
   * Gets the DD Committee houseType.
   *
   * @return string
   *   HouseType of the DD Committee.
   */
  public function getHouseType();

  /**
   * Sets the DD Committee houseType.
   *
   * @param string $house_type
   *   The DD Committee houseType.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setHouseType($house_type);

  /**
   * Gets the DD Committee hids.
   *
   * @return string
   *   Hids of the DD Committee.
   */
  public function getHids();

  /**
   * Sets the DD Committee hids.
   *
   * @param string $hids
   *   The DD Committee hids.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setHids($hids);

  /**
   * Gets the DD Committee vids.
   *
   * @return string
   *   Vids of the DD Committee.
   */
  public function getVids();

  /**
   * Sets the DD Committee vids.
   *
   * @param string $vids
   *   The DD Committee vids.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setVids($vids);

  /**
   * Gets the DD Committee room.
   *
   * @return string
   *   Room of the DD Committee.
   */
  public function getRoom();

  /**
   * Sets the DD Committee room.
   *
   * @param string $room
   *   The DD Committee room.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setRoom($room);

  /**
   * Gets the DD Committee phone.
   *
   * @return string
   *   Phone of the DD Committee.
   */
  public function getPhone();

  /**
   * Sets the DD Committee phone.
   *
   * @param string $phone
   *   The DD Committee phone.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setPhone($phone);

  /**
   * Gets the DD Committee fax.
   *
   * @return string
   *   Fax of the DD Committee.
   */
  public function getFax();

  /**
   * Sets the DD Committee fax.
   *
   * @param string $fax
   *   The DD Committee fax.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setFax($fax);

  /**
   * Gets the DD Committee email.
   *
   * @return string
   *   Email of the DD Committee.
   */
  public function getEmail();

  /**
   * Sets the DD Committee email.
   *
   * @param string $email
   *   The DD Committee email.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setEmail($email);

  /**
   * Gets the DD Committee session year.
   *
   * @return string
   *   Session year of the DD Committee.
   */
  public function getSessionYear();

  /**
   * Sets the DD Committee session year.
   *
   * @param string $session_year
   *   The DD Committee session year.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Committee is current .
   *
   * @return bool
   *   Is Current of the DD Committee.
   */
  public function getIsCurrent();

  /**
   * Sets the DD Committee is current.
   *
   * @param bool $is_current
   *   The DD Committee is current.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setIsCurrent($is_current);

  /**
   * Gets the DD Committee Name ID.
   *
   * @return int
   *   Name ID of the DD Committee.
   */
  public function getCommitteeNameId();

  /**
   * Sets the DD Committee Name ID.
   *
   * @param int $cn_id
   *   The DD Committee Name ID.
   *
   * @return \Drupal\dd_committee\Entity\DdCommitteeInterface
   *   The called DD Committee entity.
   */
  public function setCommitteeNameId($cn_id);

  /**
   * Returns the DD Committee published status indicator.
   *
   * Unpublished DD Committee are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the DD Committee is published.
   */
  public function isPublished();
}
