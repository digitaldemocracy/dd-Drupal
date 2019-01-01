<?php

namespace Drupal\dd_organization\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Organization entities.
 *
 * @ingroup dd_organization
 */
interface DdOrganizationInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Organization name.
   *
   * @return string
   *   Name of the DD Organization.
   */
  public function getName();

  /**
   * Sets the DD Organization name.
   *
   * @param string $name
   *   The DD Organization name.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationInterface
   *   The called DD Organization entity.
   */
  public function setName($name);

  /**
   * Gets the DD Organization city.
   *
   * @return string
   *   City of the DD Organization.
   */
  public function getCity();

  /**
   * Sets the DD Organization city.
   *
   * @param string $city
   *   The DD Organization city.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationInterface
   *   The called DD Organization entity.
   */
  public function setCity($city);


  /**
   * Gets the DD Organization stateHeadquartered.
   *
   * @return string
   *   StateHeadquartered of the DD Organization.
   */
  public function getStateHeadquartered();

  /**
   * Sets the DD Organization state_headquartered.
   *
   * @param string $state_headquartered
   *   The DD Organization stateHeadquartered.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationInterface
   *   The called DD Organization entity.
   */
  public function setStateHeadquartered($state_headquartered);

  /**
   * Gets the DD Organization type.
   *
   * @return string
   *   Type of the DD Organization.
   */
  public function getType();

  /**
   * Sets the DD Organization type.
   *
   * @param string $type
   *   The DD Organization type.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationInterface
   *   The called DD Organization entity.
   */
  public function setType($type);

}
