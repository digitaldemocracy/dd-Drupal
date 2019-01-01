<?php

namespace Drupal\dd_base;
use Drupal\user\UserInterface;

/**
 * Class DdUser
 * @package Drupal\dd_base
 */
class DdUser {
  protected $firstName;
  protected $lastName;
  protected $email;
  protected $street;
  protected $city;
  protected $zip;
  protected $state;
  protected $uid;

  /**
   * Get First Name.
   *
   * @return string
   *   First Name
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * Set First Name.
   *
   * @param string $first_name
   *   First Name
   */
  public function setFirstName($first_name) {
    $this->firstName = $first_name;
  }

  /**
   * Get Last Name.
   *
   * @return string
   *   Last Name
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * Set Last Name.
   *
   * @param string $last_name
   *   Last Name
   */
  public function setLastName($last_name) {
    $this->lastName = $last_name;
  }

  /**
   * Get Email.
   *
   * @return string
   *   Email
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set Email.
   *
   * @param string $email
   *   Email
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * Get Street Address.
   *
   * @return string
   *   Street Address
   */
  public function getStreet() {
    return $this->street;
  }

  /**
   * Set Street Address.
   *
   * @param string $street
   *   Street Address
   */
  public function setStreet($street) {
    $this->street = $street;
  }

  /**
   * Get City.
   *
   * @return string
   *   City
   */
  public function getCity() {
    return $this->city;
  }

  /**
   * Set City.
   *
   * @param string $city
   *   City
   */
  public function setCity($city) {
    $this->city = $city;
  }

  /**
   * Get Zip.
   *
   * @return string
   *   Zip
   */
  public function getZip() {
    return $this->zip;
  }

  /**
   * Set Zip.
   *
   * @param string $zip
   *   Zip
   */
  public function setZip($zip) {
    $this->zip = $zip;
  }

  /**
   * Get State.
   *
   * @return string
   *   State
   */
  public function getState() {
    return $this->state;
  }

  /**
   * Set State.
   *
   * @param string $state
   *   State
   */
  public function setState($state) {
    $this->state = $state;
  }

  /**
   * Get UID.
   *
   * @return int
   *   UID
   */
  public function getUid() {
    return $this->uid;
  }

  /**
   * Set UID.
   *
   * @param int $uid
   *   UID
   */
  public function setUid($uid) {
    $this->uid = $uid;
  }

  /**
   * Build DdUser Object from User Object.
   *
   * @param UserInterface $user
   *   User
   */
  public function createFromUser(UserInterface $user) {

    // Ensure address information exists.
    $field_address = $user->get('field_address')[0];
    $this->setFirstName($user->get('field_first_name')->value);
    $this->setLastName($user->get('field_last_name')->value);
    $this->setEmail($user->get('mail')->value);
    $this->setUid($user->id());

    if ($field_address) {
      $this->setStreet($field_address->get('address_line1')->getValue());
      $this->setCity($field_address->get('locality')->getValue());
      $this->setState($field_address->get('administrative_area')->getValue());
      $this->setZip($field_address->get('postal_code')->getValue());
    }
  }

  /**
   * Checks that name/address fields are filled in.
   *
   * @return bool
   *   TRUE if filled in, false otherwise.
   */
  public function validateFields() {
    if (
      !empty($this->getFirstName()) &&
      !empty($this->getLastName()) &&
      !empty($this->getStreet()) &&
      !empty($this->getCity()) &&
      !empty($this->getState()) &&
      !empty($this->getZip())
    ) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get array of user fields.
   *
   * @return array
   *   Array of user data.
   */
  public function toArray() {
    return [
      'first' => $this->getFirstName(),
      'last' => $this->getLastName(),
      'name' => $this->getFirstName() . ' ' . $this->getLastName(),
      'email' => $this->getEmail(),
      'street' => $this->getStreet(),
      'city' => $this->getCity(),
      'state' => $this->getState(),
      'zipcode' => $this->getZip(),
    ];
  }

  /**
   * Get array of user fields.
   *
   * @return array
   *   Array of user data.
   */
  public function toEmailFormArray() {
    return [
      'first' => $this->getFirstName(),
      'last' => $this->getLastName(),
      'name' => $this->getFirstName() . ' ' . $this->getLastName(),
      'email' => $this->getEmail(),
      'street' => $this->getStreet(),
      'city' => $this->getCity(),
      'state' => $this->getState(),
      'zip' => $this->getZip(),
    ];
  }
}
