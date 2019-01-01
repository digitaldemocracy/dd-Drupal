<?php

namespace Drupal\dd_clean_contents;

use Drupal\Core\Entity\Schema\DynamicallyFieldableEntityStorageSchemaInterface;
use Drupal\Core\Entity\Schema\EntityStorageSchemaInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Entity\EntityDefinitionUpdateManager;

/**
 * Manages entity definition updates.
 */
class DdEntityDefinitionUpdateManager extends EntityDefinitionUpdateManager {

  /**
   * {@inheritdoc}
   */
  public function applyUpdates() {
    $complete_change_list = $this
      ->getChangeList();
    if ($complete_change_list) {

      // self::getChangeList() only disables the cache and does not invalidate.
      // In case there are changes, explicitly invalidate caches.
      $this->entityManager
        ->clearCachedDefinitions();
    }
    foreach ($complete_change_list as $entity_type_id => $change_list) {

      try {
        // Process entity type definition changes before storage definitions ones
        // this is necessary when you change an entity type from non-revisionable
        // to revisionable and at the same time add revisionable fields to the
        // entity type.
        if (!empty($change_list['entity_type'])) {
          $this
            ->doEntityUpdate($change_list['entity_type'], $entity_type_id);
        }

        // Process field storage definition changes.
        if (!empty($change_list['field_storage_definitions'])) {
          $storage_definitions = $this->entityManager
            ->getFieldStorageDefinitions($entity_type_id);
          $original_storage_definitions = $this->entityManager
            ->getLastInstalledFieldStorageDefinitions($entity_type_id);
          foreach ($change_list['field_storage_definitions'] as $field_name => $change) {
            $storage_definition = isset($storage_definitions[$field_name]) ? $storage_definitions[$field_name] : NULL;
            $original_storage_definition = isset($original_storage_definitions[$field_name]) ? $original_storage_definitions[$field_name] : NULL;
            $this
              ->doFieldUpdate($change, $storage_definition, $original_storage_definition);
          }
        }
      }
      catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
      }
    }
  }
}

