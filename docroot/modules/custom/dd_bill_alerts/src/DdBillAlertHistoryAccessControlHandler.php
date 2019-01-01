<?php

namespace Drupal\dd_bill_alerts;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dd bill alert history entity.
 *
 * @see \Drupal\dd_bill_alerts\Entity\DdBillAlertHistory.
 */
class DdBillAlertHistoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_bill_alerts\Entity\DdBillAlertHistoryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd bill alert history entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd bill alert history entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd bill alert history entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd bill alert history entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd bill alert history entities');
  }

}
