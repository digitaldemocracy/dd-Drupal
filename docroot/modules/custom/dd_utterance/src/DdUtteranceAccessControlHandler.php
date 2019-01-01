<?php

namespace Drupal\dd_utterance;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the DD Utterance entity.
 *
 * @see \Drupal\dd_utterance\Entity\DdUtterance.
 */
class DdUtteranceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dd_utterance\Entity\DdUtteranceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dd utterance entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dd utterance entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dd utterance entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }
}
