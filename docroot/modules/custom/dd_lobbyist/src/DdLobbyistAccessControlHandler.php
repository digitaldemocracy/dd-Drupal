<?php

namespace Drupal\dd_lobbyist;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Lobbyist entity.
 *
 * @see \Drupal\dd_lobbyist\Entity\DdLobbyist.
 */
class DdLobbyistAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_lobbyist\Entity\DdLobbyistInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd lobbyist entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd lobbyist entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd lobbyist entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
