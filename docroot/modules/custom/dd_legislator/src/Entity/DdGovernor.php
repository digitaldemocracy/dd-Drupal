<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD Governor entity.
 *
 * @ingroup dd_governor
 *
 * @ContentEntityType(
 *   id = "dd_governor",
 *   label = @Translation("DD Governor"),
 *   handlers = {
 *     "storage" = "Drupal\dd_legislator\Entity\Sql\DdGovernorSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_governor\Entity\DdGovernorViewsData",
 *   },
 *   base_table = "Governor",
 *   translatable = FALSE,
 *   admin_permission = "administer dd_legislator entities",
 *   entity_keys = {
 *     "id" = "pid",
 *   },
 * )
 */
class DdGovernor extends DdPersonContentEntityBase implements DdGovernorInterface {

  /**
   * Get Current Governor.
   *
   * @return DdGovernor
   *   Governor entity.
   */
  public static function getCurrentGovernor() {
    $governors = self::loadMultiple();
    return reset($governors);
  }
  /**
   * {@inheritdoc}
   */
  public function getOffice() {
    return $this->get('office')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setOffice($office) {
    $this->set('office', $office);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMailingAddress() {
    return $this->get('mailing_address')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMailingAddress($mailing_address) {
    $this->set('mailing_address', $mailing_address);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getCity() {
    return $this->get('city')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCity($city) {
    $this->set('city', $city);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getZip() {
    return $this->get('zip')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setZip($zip) {
    $this->set('zip', $zip);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhone() {
    return $this->get('phone')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPhone($phone) {
    $this->set('phone', $phone);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFax() {
    return $this->get('fax')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFax($fax) {
    $this->set('fax', $fax);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email) {
    $this->set('email', $email);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getFirstName() . ' ' . $this->getLastName();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['office'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Office'))
      ->setDescription(t('The office of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['mailing_address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Mailing Address'))
      ->setDescription(t('The mailing Address of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDescription(t('The city of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['zip'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Zip'))
      ->setDescription(t('The zip of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setDescription(t('The phone of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['fax'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Fax'))
      ->setDescription(t('The fax of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email'))
      ->setDescription(t('The email of the DD Governor entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
