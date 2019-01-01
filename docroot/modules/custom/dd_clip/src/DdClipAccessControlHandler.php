<?php

namespace Drupal\dd_clip;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Clip entity.
 *
 * @see \Drupal\dd_clip\Entity\DdClip.
 */
class DdClipAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_clip\Entity\DdClipInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd clip entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd clip entities');

      case 'update':
        if ($account->hasPermission('edit any dd clip entities')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        else {
          return AccessResult::allowedIf($account->hasPermission('edit own dd clip entities') && ($account->id() == $entity->getOwnerId()))->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);
        }

      case 'delete':
        if ($account->hasPermission('delete any dd clip entities')) {
          return AccessResult::allowed()->cachePerPermissions();
        }
        else {
          return AccessResult::allowedIf($account->hasPermission('delete own dd clip entities') && ($account->id() == $entity->getOwnerId()))->cachePerPermissions()->cachePerUser()->addCacheableDependency($entity);
        }
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd clip entities');
  }

}
