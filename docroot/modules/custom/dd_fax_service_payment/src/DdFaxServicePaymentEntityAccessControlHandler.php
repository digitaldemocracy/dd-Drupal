<?php

namespace Drupal\dd_fax_service_payment;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dd fax service payment entity entity.
 *
 * @see \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity.
 */
class DdFaxServicePaymentEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd fax service payment entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd fax service payment entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd fax service payment entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd fax service payment entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd fax service payment entity entities');
  }

}
