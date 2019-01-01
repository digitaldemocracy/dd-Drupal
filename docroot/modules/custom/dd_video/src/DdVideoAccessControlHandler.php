<?php

namespace Drupal\dd_video;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Video entity.
 *
 * @see \Drupal\dd_video\Entity\DdVideo.
 */
class DdVideoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_video\Entity\DdVideoInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd video entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd video entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd video entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
