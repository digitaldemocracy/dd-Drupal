<?php

namespace Drupal\dd_hearing;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Hearing entity.
 *
 * @see \Drupal\dd_hearing\Entity\DdHearing.
 */
class DdHearingAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_hearing\Entity\DdHearingInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd hearing entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd hearing entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd hearing entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
