<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD General Public entity.
 *
 * @see \Drupal\dd_person\Entity\DdGeneralPublic.
 */
class DdGeneralPublicAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_person\Entity\DdGeneralPublicInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd general public entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd general public entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd general public entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
