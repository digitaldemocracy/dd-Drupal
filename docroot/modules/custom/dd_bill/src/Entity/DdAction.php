<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Action entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_action",
 *   label = @Translation("DD Action"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_bill\DdBillAccessControlHandler",
 *     "views_data" = "Drupal\dd_bill\Entity\DdActionViewsData"
 *   },
 *   base_table = "Action",
 *   admin_permission = "administer dd action entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "bid",
 *   },
 * )
 */
class DdAction extends DdBaseContentEntity implements DdActionInterface {
  /**
   * @inheritDoc
   */
  public function getVid() {
    return $this->get('vid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setVid($vid) {
    $this->set('vid', $vid);
  }

  /**
   * @inheritDoc
   */
  public function getBid() {
    return $this->get('bid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setBid($bid) {
    $this->set('bid', $bid);
  }

  /**
   * @inheritDoc
   */
  public function getDate() {
    return $this->get('date_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDate($date) {
    $this->set('date_ts', $date);
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

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Text'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
