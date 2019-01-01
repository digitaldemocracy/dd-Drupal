<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Person entity.
 *
 * @see \Drupal\dd_person\Entity\DdPerson.
 */
class DdPersonAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_person\Entity\DdPersonInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd_person entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd_person entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd_person entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
