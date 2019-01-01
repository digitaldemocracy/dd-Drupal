<?php

namespace Drupal\dd_committee;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Committee Authors entity.
 *
 * @see \Drupal\dd_committee\Entity\DdCommitteeAuthors.
 */
class DdCommitteeAuthorsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_committee\Entity\DdCommitteeAuthorsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd committee authors entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd committee authors entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd committee authors entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd committee authors entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd committee authors entities');
  }

}
