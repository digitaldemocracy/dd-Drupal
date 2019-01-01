<?php

namespace Drupal\dd_fax_service;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dd fax service history entity.
 *
 * @see \Drupal\dd_fax_service\Entity\DdFaxServiceHistory.
 */
class DdFaxServiceHistoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd fax service history entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd fax service history entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd fax service history entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dd fax service history entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dd fax service history entities');
  }

}
