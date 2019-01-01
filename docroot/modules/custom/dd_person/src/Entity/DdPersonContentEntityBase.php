<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

class DdPersonContentEntityBase extends DdBaseStateField implements DdPersonContentEntityInterface {
  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFirstName() {
    return $this->get('first')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstName($name) {
    $this->set('first', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMiddleName() {
    return $this->get('middle')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMiddleName($name) {
    $this->set('middle', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastName() {
    return $this->get('last')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastName($name) {
    $this->set('last', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSuffix() {
    return $this->get('suffix')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSuffix($suffix) {
    $this->set('suffix', $suffix);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getImage() {
    return $this->get('image')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setImage($name) {
    $this->set('image', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFullNameLastFirst() {
    return $this->getLastName()
      . ($this->getSuffix() ? ' ' . $this->getSuffix() : '')
      . ', ' . $this->getFirstName();
  }

  /**
   * {@inheritdoc}
   */
  public function setFullNameLastFirst() {
    $this->set('fullname_lastfirst', $this->getLastName()
      . ($this->getSuffix() ? ' ' . $this->getSuffix() : '')
      . ', ' . $this->getFirstName());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFullNameFirstLast() {
    return $this->getFirstName() . ' ' . $this->getLastName()
      . ($this->getSuffix() ? ' ' . $this->getSuffix() : '');
  }

  /**
   * {@inheritdoc}
   */
  public function setFullNameFirstLast() {
    $this->set('fullname_firstlast', $this->getFirstName() . ' '
      . $this->getLastName()
      . ($this->getSuffix() ? ' ' . $this->getSuffix() : '')
    );
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getServesOnDrIds() {
    return $this->get('servesOn_dr_ids')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setServesOnDrIds($dr_ids) {
    $this->set('servesOn_dr_ids', $dr_ids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['first'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('The first name of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 1,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['middle'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Middle Name'))
      ->setDescription(t('The middle name of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 2,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 2,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['last'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t('The last name of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
      ->setDescription(t('The suffix of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['fullname_lastfirst'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full Name - Last, First'))
      ->setDescription(t('The full name of the DD Person entity - last, first'))
      ->setComputed(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 4,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['fullname_firstlast'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Full Name - First Last'))
      ->setDescription(t('The full name of the DD Person entity - first last'))
      ->setComputed(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 4,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['image'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Image filename'))
      ->setDescription(t('The image filename of the DD Person entity.'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    // Exclude dd_serves_on from itself.
    if ($entity_type->id() != 'dd_serves_on') {
      $fields['servesOn_dr_ids'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('servesOn Drupal IDs'))
        ->setSetting('target_type', 'dd_serves_on')
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE)
        ->setComputed(TRUE);
    }

    return $fields;
  }
}
