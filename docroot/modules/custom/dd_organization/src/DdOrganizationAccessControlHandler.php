<?php

namespace Drupal\dd_organization;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Organization entity.
 *
 * @see \Drupal\dd_organization\Entity\DdOrganization.
 */
class DdOrganizationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_organization\Entity\DdOrganizationInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd organization entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd organization entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd organization entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
