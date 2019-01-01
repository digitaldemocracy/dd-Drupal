<?php

namespace Drupal\dd_base\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Checks access for /user/{user}/* routes.
 */
class DdUserAccessCheck implements AccessInterface {

  /**
   * Access check for logged in user w/user route.
   *
   * @param int $user
   *   User ID from route.
   * @param AccountInterface $account
   *   Current logged in account
   *
   * @return AccessResult
   *   Allowed or not.
   */
  public function access($user, AccountInterface $account) {
    return AccessResult::allowedIf($account->id() == $user);
  }
}
