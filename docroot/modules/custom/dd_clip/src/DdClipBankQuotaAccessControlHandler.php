<?php

namespace Drupal\dd_clip;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Clip Bank Quota entity.
 *
 * @see \Drupal\dd_clip\Entity\DdClipBankQuota.
 */
class DdClipBankQuotaAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_clip\Entity\DdClipBankQuotaInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd clip bank quota entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd clip bank quota entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd clip bank quota entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd clip bank quota entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd clip bank quota entities');
  }

}
