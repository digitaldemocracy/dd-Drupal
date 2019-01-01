<?php

namespace Drupal\dd_payment_system;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Subscription Plan entity.
 *
 * @see \Drupal\dd_payment_system\Entity\DdSubscriptionPlan.
 */
class DdSubscriptionPlanAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_payment_system\Entity\DdSubscriptionPlanInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd subscription plan entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd subscription plan entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd subscription plan entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd subscription plan entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd subscription plan entities');
  }

}
