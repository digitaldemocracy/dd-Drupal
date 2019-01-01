<?php

namespace Drupal\dd_legislator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Term entity.
 *
 * @see \Drupal\dd_legislator\Entity\DdTerm.
 */
class DdTermAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_legislator\Entity\DdTermInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd term entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd term entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd term entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
