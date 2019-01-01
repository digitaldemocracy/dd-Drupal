<?php

namespace Drupal\dd_bill;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Bill entity.
 *
 * @see \Drupal\dd_bill\Entity\DdBill.
 */
class DdBillAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_bill\Entity\DdBillInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd bill entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd bill entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd bill entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

}
