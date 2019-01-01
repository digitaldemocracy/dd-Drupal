<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Combined Alignments entity.
 *
 * @see \Drupal\dd_person\Entity\DdAuthors.
 */
class DdCombinedAlignmentsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_person\Entity\DdCombinedAlignmentsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd combined alignments entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd combined alignments entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd combined alignments entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
