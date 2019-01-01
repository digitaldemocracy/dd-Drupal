<?php

namespace Drupal\dd_metrics;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Campaign visitor entity.
 *
 * @see \Drupal\dd_metrics\Entity\DdCampaignVisitor.
 */
class DdCampaignVisitorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_metrics\Entity\DdCampaignVisitorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd campaign visitor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd campaign visitor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd campaign visitor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
