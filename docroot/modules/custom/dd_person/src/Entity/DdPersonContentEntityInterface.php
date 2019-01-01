<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Person entities.
 *
 * @ingroup dd_person
 */
interface DdPersonContentEntityInterface extends DdBaseStateFieldInterface {
  /**
   * Gets the DD Person first name.
   *
   * @return string
   *   First name of the DD Person.
   */
  public function getFirstName();

  /**
   * Sets the DD Person first name.
   *
   * @param string $name
   *   The DD Person first name.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setFirstName($name);

  /**
   * Gets the DD Person middle name.
   *
   * @return string
   *   Middle name of the DD Person.
   */
  public function getMiddleName();

  /**
   * Sets the DD Person middle name.
   *
   * @param string $name
   *   The DD Person middle name.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setMiddleName($name);

  /**
   * Gets the DD Person last name.
   *
   * @return string
   *   Last name of the DD Person.
   */
  public function getLastName();

  /**
   * Sets the DD Person last name.
   *
   * @param string $name
   *   The DD Person last name.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setLastName($name);

  /**
   * Gets the DD Person Suffix.
   *
   * @return string
   *   suffix of person.
   */
  public function getSuffix();

  /**
   * Sets the DD Person suffix.
   *
   * @param string $suffix
   *   suffix of person.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setSuffix($suffix);

  /**
   * Gets the DD Person Title.
   *
   * @return string
   *   title of person.
   */
  public function getTitle();

  /**
   * Sets the DD Person suffix.
   *
   * @param string $title
   *   title of person.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setTitle($title);

  /**
   * Gets the Person image filename.
   *
   * @return string
   *   Image filename of the DD Person.
   */
  public function getImage();

  /**
   * Sets the DD Person image filename.
   *
   * @param string $filename
   *   The DD Person image filename.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setImage($filename);

  /**
   * Gets the DD Person full name - last, first.
   *
   * @return string
   *   Last, First name of the DD Person.
   */
  public function getFullNameLastFirst();

  /**
   * Sets the DD Person full name - last, first.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setFullNameLastFirst();

  /**
   * Gets the DD Person full name - first last.
   *
   * @return string
   *   Last, First name of the DD Person.
   */
  public function getFullNameFirstLast();

  /**
   * Sets the DD Person full name - first last.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setFullNameFirstLast();

  /**
   * Gets the Person servesOn Drupal IDs.
   *
   * @return array
   *   servesOn Drupal IDs of the DD Person.
   */
  public function getServesOnDrIds();

  /**
   * Sets the Person servesOn Drupal IDs.
   *
   * @param array $dr_ids
   *   The DD Person servesOn Drupal IDs.
   *
   * @return \Drupal\dd_person\Entity\DdPersonContentEntityInterface
   *   The called DD Person entity.
   */
  public function setServesOnDrIds($dr_ids);

}
