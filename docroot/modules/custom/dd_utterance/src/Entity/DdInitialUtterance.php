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
 * Defines the DD Initial Utterance entity.
 *
 * @ingroup dd_utterance
 *
 * @ContentEntityType(
 *   id = "dd_initial_utterance",
 *   label = @Translation("DD Initial Utterance"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_utterance\DdUtteranceAccessControlHandler",
 *     "views_data" = "Drupal\dd_utterance\Entity\DdInitialUtteranceViewsData",
 *   },
 *   base_table = "InitialUtterance",
 *   persistent_cache = FALSE,
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "uid",
 *   },
 * )
 */
class DdInitialUtterance extends DdPersonContentEntityBase implements DdInitialUtteranceInterface {

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
  public function getUid() {
    return $this->get('uid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setUid($uid) {
    $this->set('uid', $uid);
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
    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Utterance ID'))
      ->setSetting('target_type', 'dd_utterance')
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Discussion ID'))
      ->setSetting('target_type', 'dd_bill_discussion')
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
