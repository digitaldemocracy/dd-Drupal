<?php

namespace Drupal\dd_legislator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Legislative Staff entity.
 *
 * @see \Drupal\dd_legislator\Entity\DdLegislativeStaff.
 */
class DdLegislativeStaffAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_legislator\Entity\DdLegislativeStaffInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd legislative staff entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd legislative staff entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd legislative staff entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
