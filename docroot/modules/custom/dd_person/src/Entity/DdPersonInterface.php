<?php

namespace Drupal\dd_person\Entity;

/**
 * Provides an interface for defining DD Person entities.
 *
 * @ingroup dd_person
 */
interface DdPersonInterface extends DdPersonContentEntityInterface {
  /**
   * Gets the DD Person Has Spoken.
   *
   * @return bool
   *   TRUE if has spoken, false otherwise.
   */
  public function getHasSpoken();

  /**
   * Sets the DD Person Has Spoken.
   *
   * @param bool $has_spoken
   *   Boolean has spoken.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setHasSpoken($has_spoken);

  /**
   * Gets the DD Person Classifications.
   *
   * @return array
   *   Classifications of person.
   */
  public function getClassifications();

  /**
   * Sets the DD Person Classifications.
   *
   * @param array $classifications
   *   Array of classifications.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setClassifications($classifications);

  /**
   * Gets the DD Person Current Classifications.
   *
   * @return array
   *   Classifications of person.
   */
  public function getCurrentClassifications();

  /**
   * Sets the DD Person Current Classifications.
   *
   * @param array $classifications
   *   Array of current classifications.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setCurrentClassifications($classifications);


  /**
   * Gets the DD Person Affiliations.
   *
   * @return array
   *   Affiliations of person.
   */
  public function getAffiliations();

  /**
   * Sets the DD Person Affiliations.
   *
   * @param array $affiliations
   *   Affiliations array.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setAffiliations($affiliations);

  /**
   * Gets the DD Person Current Affiliations.
   *
   * @return array
   *   Affiliations of person.
   */
  public function getCurrentAffiliations();

  /**
   * Sets the DD Person Current Affiliations.
   *
   * @param array $affiliations
   *   Affiliations array.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setCurrentAffiliations($affiliations);

  /**
   * Gets the DD Person Classifications DrIds.
   *
   * @return array
   *   Classifications dr_ids of person.
   */
  public function getClassificationDrIds();

  /**
   * Sets the DD Person Classification DrIds.
   *
   * @param array $classification_dr_ids
   *   Array of classifications dr ids.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setClassificationDrIds($classification_dr_ids);

  /**
   * Gets the DD Person Current Classifications DrIds.
   *
   * @return array
   *   Current classifications dr_ids of person.
   */
  public function getCurrentClassificationDrIds();

  /**
   * Sets the DD Person Current Classification DrIds.
   *
   * @param array $classification_dr_ids
   *   Array of classifications dr ids.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setCurrentClassificationDrIds($classification_dr_ids);


  /**
   * Gets the DD Person Affiliations OIds.
   *
   * @return array
   *   Affiliations Oids of person.
   */
  public function getAffiliationOIds();

  /**
   * Sets the DD Person Affiliation Oids.
   *
   * @param array $affiliation_oids
   *   Affiliations oids array.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setAffiliationOids($affiliation_oids);

  /**
   * Gets the DD Person Current Affiliations Oids.
   *
   * @return array
   *   Current affiliations Oids of person.
   */
  public function getCurrentAffiliationOids();

  /**
   * Sets the DD Person Current Affiliation Oids.
   *
   * @param array $affiliation_oids
   *   Affiliations oids array.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setCurrentAffiliationOids($affiliation_oids);

  /**
   * Gets the DD Person Last Name Alpha.
   *
   * @return string
   *   Last Name Alpha of person.
   */
  public function getLastNameAlpha();

  /**
   * Sets the DD Person Last Name Alpha.
   *
   * @param string $last_name_alpha
   *   Last name alpha of person.
   *
   * @return \Drupal\dd_person\Entity\DdPerson
   *   The called DD Person entity.
   */
  public function setLastNameAlpha($last_name_alpha);
}
