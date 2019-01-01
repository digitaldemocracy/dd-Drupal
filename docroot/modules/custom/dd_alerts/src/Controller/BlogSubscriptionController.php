<?php

namespace Drupal\dd_alerts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * An empty page controller.
 */
class BlogSubscriptionController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function subscribe(AccountInterface $user, Request $request) {
    $current_user = \Drupal::currentUser();

    // Check if this user has a subscription already.
    $check_nodes = \Drupal::entityQuery('node');
    $check_nodes->condition('type', 'dd_commentary_alert');
    $check_nodes->condition('uid', $current_user->id());
    $check_nodes->condition('field_user', $user->id());
    $count = $check_nodes->execute();

    // If not, go forth.
    $status = FALSE;
    if (empty($count)) {
      $status = Node::create([
        'type' => 'dd_commentary_alert',
        'uid' => $current_user->id(),
        'title' => 'Blog subscription for ' . $user->getUsername(),
        'field_user' => $user->id(),
      ])->save();

      if ($status) {
        drupal_set_message(t('Subscription registered.'));
      }
      else {
        drupal_set_message(t('Subscription registration failed, please try again.'), 'error');
      }
    }
    else {
      drupal_set_message(t('Subscription already exists for this blog.'), 'error');
    }

    // Redirect back to their subscriptions.
    $dashboard = Url::fromRoute('dd_account_dashboard.commentary_alerts', [
      'user' => $current_user->id(),
    ])->toString();
    return new RedirectResponse($dashboard);
  }

  /**
   * {@inheritdoc}
   */
  public function unsubscribe(AccountInterface $user, Request $request) {
    $current_user = \Drupal::currentUser();

    // Check if this user has a subscription already.
    $check_nodes = \Drupal::entityQuery('node');
    $check_nodes->condition('type', 'dd_commentary_alert');
    $check_nodes->condition('uid', $current_user->id());
    $check_nodes->condition('field_user', $user->id());
    $ids = $check_nodes->execute();
    $ids = array_values($ids);
    if (!empty($ids)) {
      $node = Node::load(array_shift($ids));
      $node->delete();
      drupal_set_message(t('Subscription unregistered.'));
    }
    else {
      drupal_set_message(t('Subscription not found.'), 'error');
    }

    // Redirect back to their subscriptions.
    $dashboard = Url::fromRoute('dd_account_dashboard.commentary_alerts', [
      'user' => $current_user->id(),
    ])->toString();
    return new RedirectResponse($dashboard);
  }

}
