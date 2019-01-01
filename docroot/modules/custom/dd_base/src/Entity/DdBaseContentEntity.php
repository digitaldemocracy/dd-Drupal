<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Class DdBaseContentEntity
 *
 * Includes functions for all dd custom entity classes to inherit.
 * @package Drupal\dd_base\Entity
 */
class DdBaseContentEntity extends ContentEntityBase {
  /**
   * {@inheritdoc}
   */
  public static function loadByFields(array $fields_vals, array $order_bys = NULL) {
    $entity_manager = \Drupal::entityManager();
    return $entity_manager->getStorage($entity_manager->getEntityTypeFromClass(get_called_class()))
      ->loadByFields($fields_vals, $order_bys);
  }

  /**
   * Get Date Last Touched.
   */
  public function getLastTouched() {
    return $this->get('lastTouched_ts')->value;
  }

  /**
   * Set Date Last Touched.
   *
   * @param int $date
   *   Timestamp date last touched.
   *
   * @return \Drupal\dd_base\Entity\DdBaseContentEntity
   *   The called DdBaseContentEntity entity.
   */
  public function setLastTouched($date) {
    $this->set('lastTouched_ts', $date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add lastTouched_ts fields to entities.
    $date_last_touched_entities = [
      'dd_bill',
      'dd_bill_version',
      'dd_bill_vote_summary',
      'dd_current_utterance',
      'dd_hearing',
      'dd_hearing_agenda',
      'dd_utterance',
      'dd_video',
      'dd_bill_discussion',
    ];

    if (in_array($entity_type->id(), $date_last_touched_entities)) {
      $fields['lastTouched_ts'] = BaseFieldDefinition::create('timestamp')
        ->setLabel(t('Date Last Touched'))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }
}
