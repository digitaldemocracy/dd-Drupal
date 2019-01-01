<?php

namespace Drupal\dd_action_center\Utility;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DdActionCenterCampaignHelper {
  /**
   * Get visitor's user object, uses tempstore if anonymous.
   *
   * Checks for a valid address/city/state.
   *
   * @return User
   *   Logged in user if has address info,
   *   otherwise anonymous user object if has address info,
   *   otherwise NULL.
   */
  public static function getVisitor() {
    $users = [];

    // Check logged in user first.
    $uid = \Drupal::currentUser()->id();
    if ($uid > 0) {
      $user = User::load($uid);
      if ($user) {
        $users[] = $user;
      }
    }

    // Try for anonymous tempstore.
    $tempstore = \Drupal::service('user.private_tempstore')
      ->get('dd_campaign_visitor');
    $user = $tempstore->get('user');
    if ($user) {
      $users[] = $user;
    }

    if ($users) {
      foreach ($users as $user) {
        $missing_info = FALSE;
        // Ensure address information exists.
        $field_address = $user->get('field_address')[0];

        if ($field_address) {
          $address = $field_address->get('address_line1')->getValue();
          $city = $field_address->get('locality')->getValue();
          $zip = $field_address->get('postal_code')->getValue();

          if (empty($address) || empty($city) || empty($zip)) {
            $missing_info = TRUE;
          }
        }
        else {
          $missing_info = TRUE;
        }

        if (!$missing_info) {
          return $user;
        }
      }
    }
    return NULL;
  }

  /**
   * Get Campaign Wizard Class.
   *
   * @return array
   *   Class for route, or NULL if not a campaign wizard route.
   */
  public static function getCampaignWizardClass() {
    $campaign_wizard_routes = [
      'node.add' => 'campaign-wizard--details',
      'dd_action_center.dd_new_campaign_form' => 'campaign-wizard--newcampaign',
      'entity.node.edit_form' => 'campaign-wizard--details',
      'dd_action_center.dd_campaign_preview_form' => 'campaign-wizard--preview',
      'dd_action_center.dd_campaign_targets_form' => 'campaign-wizard--choose-targets',
      'dd_action_center.dd_campaign_target_actions_form' => 'campaign-wizard--target-actions',
      'dd_action_center.dd_campaign_review_target_actions_form' => 'campaign-wizard--review',
      'dd_action_center.dd_campaign_statewide_form' => 'campaign-wizard--catch-all',
      'view.campaign_stats.page_1' => 'campaign-wizard--review',
    ];

    $route = \Drupal::routeMatch();
    $route_name = $route->getRouteName();

    if ($route_name == 'node.add') {
      $node_type = $route->getParameter('node_type');
      if ($node_type->id() == 'campaign') {
        return $campaign_wizard_routes[$route_name];
      }
    }
    elseif ($route_name == 'entity.node.edit_form') {
      $node = $route->getParameter('node');
      if ($node && $node->getType() == 'campaign') {
        return $campaign_wizard_routes[$route_name];
      }
    }
    elseif (in_array($route_name, array_keys($campaign_wizard_routes))) {
      return $campaign_wizard_routes[$route_name];
    }

    return NULL;
  }

  /**
   * Assign Campaign Action to targets.
   *
   * @return RedirectResponse
   *   Response
   */
  public static function assignTargetCampaignAction() {
    $request = \Drupal::request();
    $target_ids = $request->query->get('target_ids');
    $campaign_action_id = $request->query->get('campaign_action_id');

    if (!empty($target_ids) && !empty($campaign_action_id)) {
      $ids = explode(',', $target_ids);
      foreach ($ids as $id) {
        $target_node = Node::load($id);
        $target_node->set('field_campaign_action', [['target_id' => $campaign_action_id]]);
        $target_node->save();
      }
    }

    $destination = $request->query->get('destination');
    if (!empty($destination)) {
      return new RedirectResponse($destination);
    }
  }

  /**
   * Duplicate Campaign Action group and assign to targets.
   *
   * @param bool $redirect_to_campaign
   *   If FALSE, returns ajax response of new node edit
   *   otherwise redirect response to destination.
   *
   * @return mixed
   *   RedirectResponse or New Node ID.
   */
  public static function duplicateCampaignAction($redirect_to_campaign = TRUE) {
    $request = \Drupal::request();
    $campaign_action_id = $request->query->get('campaign_action_id');

    if (!empty($campaign_action_id)) {
      $node = Node::load($campaign_action_id);
      $new_node = $node->createDuplicate();

      $name = $request->query->get('name');
      if (empty($name)) {
        $name = $node->getTitle() . ' Duplicate';
      }
      $new_node->setTitle($name);
      $new_node->save();

      // Assign targets to the new campaign action node.
      $request->query->set('campaign_action_id', $new_node->id());
      $destination = $request->query->get('destination');

      // Add duplicate_campaign_action_id to query string.
      if (!empty($destination)) {
        $destination_url = parse_url($destination);
        $query_string = '';
        if (isset($destination_url['query'])) {
          $query_string = $destination_url['query'] . '&';
        }
        $query_string .= 'duplicate_campaign_action_id=' . $new_node->id();
        $request->query->set('destination', $destination_url['path'] . '?' . $query_string);
      }
      drupal_set_message('Action Group Duplicated - Click Edit to modify actions');

      $response = self::assignTargetCampaignAction();
      if ($redirect_to_campaign) {
        return $response;
      }
      else {
        $url = Url::fromRoute('entity.node.edit_form', ['node' => $new_node->id()], ['query' => ['_wrapper_format' => 'drupal_modal']]);
        $response = new RedirectResponse($url->toString());
        return $response;
      }
    }
    else {
      $destination = $request->query->get('destination');
      if (!empty($destination)) {
        return new RedirectResponse($destination);
      }
    }
  }
}
