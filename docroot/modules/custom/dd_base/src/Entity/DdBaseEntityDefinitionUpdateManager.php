<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Entity\EntityDefinitionUpdateManager;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Manages entity definition updates.
 */
class DdBaseEntityDefinitionUpdateManager extends EntityDefinitionUpdateManager {
  /**
   * {@inheritdoc}
   */
  public function uninstallEntityType(EntityTypeInterface $entity_type) {
    if (substr($entity_type->getProvider(), 0, 3) != 'dd_') {
      parent::uninstallEntityType($entity_type);
    }
  }
}
