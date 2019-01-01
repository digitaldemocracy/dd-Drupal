<?php

namespace Drupal\dd_clip;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Video Tags entity.
 *
 * @see \Drupal\dd_clip\Entity\DdVideoTags.
 */
class DdVideoTagsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_clip\Entity\DdVideoTagsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd video tags entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd video tags entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd video tags entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd video tags entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd video tags entities');
  }

}
