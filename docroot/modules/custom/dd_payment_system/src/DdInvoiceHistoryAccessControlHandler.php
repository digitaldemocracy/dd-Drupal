<?php

namespace Drupal\dd_payment_system;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dd invoice history entity.
 *
 * @see \Drupal\dd_payment_system\Entity\DdInvoiceHistory.
 */
class DdInvoiceHistoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_payment_system\Entity\DdInvoiceHistoryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd invoice history entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd invoice history entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd invoice history entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd invoice history entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd invoice history entities');
  }

}
