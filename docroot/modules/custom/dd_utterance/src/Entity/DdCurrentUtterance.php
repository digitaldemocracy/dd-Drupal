<?php

namespace Drupal\dd_utterance\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;
use Drupal\user\UserInterface;

/**
 * Defines the DD Utterance entity.
 *
 * @ingroup dd_utterance
 *
 * @ContentEntityType(
 *   id = "dd_current_utterance",
 *   label = @Translation("DD CurrentUtterance"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_utterance\DdUtteranceAccessControlHandler",
 *   },
 *   base_table = "currentUtteranceUnsorted",
 *   persistent_cache = FALSE,
 *   entity_keys = {
 *     "id" = "uid",
 *     "label" = "text",
 *   },
 * )
 */
class DdCurrentUtterance extends DdPersonContentEntityBase implements DdCurrentUtteranceInterface {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getVid() {
    return $this->get('vid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setVid($vid) {
    $this->set('vid', $vid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getTime() {
    return $this->get('time')->value;
  }

  /**
   * @inheritDoc
   */
  public function setTime($time) {
    $this->set('time', $time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getEndTime() {
    return $this->get('endTime')->value;
  }

  /**
   * @inheritDoc
   */
  public function setEndTime($end_time) {
    $this->set('endTime', $end_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getText() {
    return $this->get('text')->value;
  }

  /**
   * @inheritDoc
   */
  public function setText($text) {
    $this->set('text', $text);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getType() {
    return $this->get('type')->value;
  }

  /**
   * @inheritDoc
   */
  public function setType($type) {
    $this->set('type', $type);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getAlignment() {
    return $this->get('alignment')->value;
  }

  /**
   * @inheritDoc
   */
  public function setAlignment($alignment) {
    $this->set('alignment', $alignment);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDid() {
    return $this->get('did')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setDid($did) {
    $this->set('did', $did);
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['vid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Video ID'))
      ->setSetting('target_type', 'dd_video')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['time'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['endTime'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('End Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Text'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['alignment'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Alignment'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Discussion ID'))
      ->setSetting('target_type', 'dd_bill_discussion')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
