<?php

namespace Drupal\dd_committee;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Committee entity.
 *
 * @see \Drupal\dd_committee\Entity\DdCommittee.
 */
class DdCommitteeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_committee\Entity\DdCommitteeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd committee entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd committee entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd committee entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
