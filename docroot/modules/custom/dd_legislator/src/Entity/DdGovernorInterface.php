<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Governor entities.
 *
 * @ingroup dd_governor
 */
interface DdGovernorInterface extends DdPersonContentEntityInterface {
  /**
   * Gets the Governor office.
   *
   * @return string
   *   Office of the Governor.
   */
  public function getOffice();

  /**
   * Sets the Governor office.
   *
   * @param string $office
   *   The Governor office.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setOffice($office);

  /**
   * Gets the Governor mailing Address.
   *
   * @return string
   *   Mailing Address of the Governor.
   */
  public function getMailingAddress();

  /**
   * Sets the Governor mailing Address.
   *
   * @param string $mailing_address
   *   The Governor mailing Address.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setMailingAddress($mailing_address);

  /**
   * Gets the Governor city.
   *
   * @return string
   *   City of the Governor.
   */
  public function getCity();

  /**
   * Sets the Governor city.
   *
   * @param string $city
   *   The Governor city.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setCity($city);

  /**
   * Gets the Governor zip.
   *
   * @return string
   *   Zip of the Governor.
   */
  public function getZip();

  /**
   * Sets the Governor zip.
   *
   * @param string $zip
   *   The Governor zip.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setZip($zip);

  /**
   * Gets the Governor phone.
   *
   * @return string
   *   Phone of the Governor.
   */
  public function getPhone();

  /**
   * Sets the Governor phone.
   *
   * @param string $phone
   *   The Governor phone.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setPhone($phone);

  /**
   * Gets the Governor fax.
   *
   * @return string
   *   Fax of the Governor.
   */
  public function getFax();

  /**
   * Sets the Governor fax.
   *
   * @param string $fax
   *   The Governor fax.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setFax($fax);

  /**
   * Gets the Governor email.
   *
   * @return string
   *   Email of the Governor.
   */
  public function getEmail();

  /**
   * Sets the Governor email.
   *
   * @param string $email
   *   The Governor email.
   *
   * @return \Drupal\dd_governor\Entity\DdGovernorInterface
   *   The called Governor entity.
   */
  public function setEmail($email);
}
