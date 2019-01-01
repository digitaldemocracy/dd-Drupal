<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Motion entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_motion",
 *   label = @Translation("DD Motion"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_bill\DdBillAccessControlHandler",
 *     "views_data" = "Drupal\dd_bill\Entity\DdMotionViewsData",
 *   },
 *   base_table = "Motion",
 *   admin_permission = "administer dd bill entities",
 *   entity_keys = {
 *     "id" = "mid",
 *     "label" = "text",
 *   },
 * )
 */
class DdMotion extends DdBaseContentEntity implements DdMotionInterface {
  /**
   * {@inheritdoc}
   */
  public function getText() {
    return $this->get('text')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setText($text) {
    $this->set('text', $text);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDoPass() {
    return $this->get('doPass')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDoPass($do_pass) {
    $this->set('doPass', $do_pass);
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

    $fields['text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Text'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['doPass'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('doPass'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
