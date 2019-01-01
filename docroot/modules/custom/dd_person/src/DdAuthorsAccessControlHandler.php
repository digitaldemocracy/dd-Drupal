<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Authors entity.
 *
 * @see \Drupal\dd_person\Entity\DdAuthors.
 */
class DdAuthorsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_person\Entity\DdAuthorsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd authors entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd authors entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd authors entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
