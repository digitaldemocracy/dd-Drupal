<?php

namespace Drupal\dd_gift_contribution;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Behests entity.
 *
 * @see \Drupal\dd_gift_contribution\Entity\DdBehests.
 */
class DdBehestsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_gift_contribution\Entity\DdBehestsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd behests entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd behests entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd behests entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

}
