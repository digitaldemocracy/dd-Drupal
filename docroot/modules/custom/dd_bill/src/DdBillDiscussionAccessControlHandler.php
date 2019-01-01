<?php

namespace Drupal\dd_bill;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Bill Discussion entity.
 *
 * @see \Drupal\dd_bill\Entity\DdBillDiscussion.
 */
class DdBillDiscussionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_bill\Entity\DdBillDiscussionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd bill discussion entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd bill discussion entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd bill discussion entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
