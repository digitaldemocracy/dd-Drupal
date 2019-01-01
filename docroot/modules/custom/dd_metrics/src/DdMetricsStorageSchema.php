<?php

namespace Drupal\dd_metrics;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the DD Metrics schema handler.
 */
class DdMetricsStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getEntitySchema(ContentEntityTypeInterface $entity_type, $reset = FALSE) {
    $schema = parent::getEntitySchema($entity_type, $reset);

    if ($entity_type->id() == 'dd_action_metrics') {
      $schema['dd_action_metrics']['indexes'] += array(
        'whitelabel_id' => array('whitelabel_id'),
        'action_conversion' => array('action_conversion'),
        'action_id' => array('action_id'),
        'created' => array('created'),
      );

    }
    elseif ($entity_type->id() == 'dd_campaign_metrics') {
      $schema['dd_campaign_metrics']['indexes'] += array(
        'metric_type' => array('metric_type'),
        'whitelabel_id' => array('whitelabel_id'),
        'total_count' => array('total_count'),
      );

    }
    elseif ($entity_type->id() == 'dd_campaign_visitor') {
      $schema['dd_campaign_visitor']['indexes'] += array(
        'campaign_id' => array('campaign_id'),
        'whitelabel_id' => array('whitelabel_id'),
        'assembly_district' => array('assembly_district'),
        'senate_district' => array('senate_district'),
        'county' => array('county'),
      );
    }
    return $schema;
  }
}
