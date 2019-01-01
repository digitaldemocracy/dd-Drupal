<?php

namespace Drupal\dd_gift_contribution;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Contribution entity.
 *
 * @see \Drupal\dd_gift_contribution\Entity\DdContribution.
 */
class DdContributionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_gift_contribution\Entity\DdContributionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd contribution entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd contribution entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd contribution entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
