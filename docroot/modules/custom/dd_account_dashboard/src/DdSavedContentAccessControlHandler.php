<?php

namespace Drupal\dd_account_dashboard;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Saved Content entity.
 *
 * @see \Drupal\dd_account_dashboard\Entity\DdSavedContent.
 */
class DdSavedContentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_account_dashboard\Entity\DdSavedContentInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd saved content entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd saved content entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd saved content entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd saved content entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd saved content entities');
  }

}
