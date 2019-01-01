<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Legislator entities.
 *
 * @ingroup dd_legislator
 */
interface DdLegislatorInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Legislator description.
   *
   * @return string
   *   Description of the Legislator.
   */
  public function getDescription();

  /**
   * Sets the Legislator description.
   *
   * @param string $description
   *   The Legislator description.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setDescription($description);


  /**
   * Gets the Legislator twitter handle.
   *
   * @return string
   *   Twitter handle of the Legislator.
   */
  public function getTwitterHandle();

  /**
   * Sets the Legislator twitter handle.
   *
   * @param string $twitter_handle
   *   The Legislator twitter handle.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setTwitterHandle($twitter_handle);


  /**
   * Gets the Legislator capitol phone.
   *
   * @return string
   *   Capitol phone of the Legislator.
   */
  public function getCapitolPhone();

  /**
   * Sets the Legislator capitol phone.
   *
   * @param string $capitol_phone
   *   The Legislator capitol phone.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setCapitolPhone($capitol_phone);

  /**
   * Gets the Legislator website URL.
   *
   * @return string
   *   Website URL of the Legislator.
   */
  public function getWebsiteURL();

  /**
   * Sets the Legislator website URL.
   *
   * @param string $website_url
   *   The Legislator website URL.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setWebsiteURL($website_url);

  /**
   * Gets the Legislator room number.
   *
   * @return string
   *   Room number of the Legislator.
   */
  public function getRoomNumber();

  /**
   * Sets the Legislator room number.
   *
   * @param string $room_number
   *   The Legislator room number.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setRoomNumber($room_number);

  /**
   * Gets the Legislator email form link.
   *
   * @return string
   *   Email Form Link of the Legislator.
   */
  public function getEmailFormLink();

  /**
   * Sets the Legislator email Form Link.
   *
   * @param string $email_form_link
   *   The Legislator email Form Link.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setEmailFormLink($email_form_link);

  /**
   * Gets the Legislator capitol fax.
   *
   * @return string
   *   Capitol fax of the Legislator.
   */
  public function getCapitolFax();

  /**
   * Sets the Legislator capitol fax.
   *
   * @param string $capitol_fax
   *   The Legislator capitol fax.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setCapitolFax($capitol_fax);

  /**
   * Gets the Legislator email.
   *
   * @return string
   *   Email of the Legislator.
   */
  public function getEmail();

  /**
   * Sets the Legislator email.
   *
   * @param string $email
   *   The Legislator email.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegislatorInterface
   *   The called Legislator entity.
   */
  public function setEmail($email);
}
