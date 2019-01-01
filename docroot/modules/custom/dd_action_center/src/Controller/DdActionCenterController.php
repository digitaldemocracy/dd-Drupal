<?php

namespace Drupal\dd_action_center\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_bill\Entity\DdBillVersionCurrent;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A Dd Action Center controller.
 */
class DdActionCenterController extends ControllerBase {
  /**
   * Perform campaign actions for modal.
   *
   * @param NodeInterface $node
   *   Campaign node.
   * @param string $action_type
   *   Action type (email/fax/letter)
   *
   * @return array
   *   Form render array.
   */
  public function campaignAction(NodeInterface $node, $action_type) {
    $body_template = '';
    $subject_template = '';
    $bill_type_number = '';
    $bill_subject = '';
    $actions = $node->get('field_actions')->referencedEntities();
    $entity_reference_type_name = $action_type . '_action';

    if ($actions) {
      foreach ($actions as $action) {
        if ($action->getType() == $entity_reference_type_name) {
          if ($action_type == 'email') {
            $body_template = $action->get('field_email_body')->value;
            $subject_template = $action->get('field_email_subject')->value;
          }
          elseif ($action_type == 'fax') {
            $body_template = $action->get('field_action_body')->value;
            $subject_template = $action->get('field_subject')->value;
          }
        }
      }
    }

    $user = DdActionCenterCampaignHelper::getVisitor();
    $token = \Drupal::token();

    $body = $token->replace(
      $body_template,
      [
        'user' => $user,
        'campaign' => $node,
      ]
    );
    $subject = $token->replace(
      $subject_template,
      [
        'user' => $user,
        'campaign' => $node,
      ]
    );


    // @todo Call email/fax/letter functions when ready.
    switch ($action_type) {
      case 'email':
        break;

      case 'fax':
        break;

      case 'letter':
        break;
    }

    // @todo Temporarily return markup.
    $markup = [
      '#type' => 'markup',
      '#markup' => $subject . $body,
      ];
    return $markup;
  }

  /**
   * Unassign Target node type Campaign Action field.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param int $target_id
   *   Target ID
   *
   * @return RedirectResponse
   *   Response
   */
  public function unassignTargetCampaignAction($campaign_id, $target_id) {
    // Load the target.
    $node = Node::load($target_id);
    if ($node) {
      // Verify campaign ID matches.
      $target_id = $node->get('field_campaign')->target_id;

      if ($target_id == $campaign_id) {
        $node->set('field_campaign_action', NULL);
        $node->save();
      }
    }

    $request = \Drupal::request();
    $destination = $request->query->get('destination');
    return new RedirectResponse($destination);
  }

  /**
   * Assign Campaign Action to targets.
   *
   * @return RedirectResponse
   *   Response
   */
  public function assignTargetCampaignAction() {
    return DdActionCenterCampaignHelper::assignTargetCampaignAction();
  }

  /**
   * Duplicate Campaign Action and assign to targets.
   *
   * @param bool $redirect_to_campaign
   *   If FALSE, returns ajax response of new node edit
   *   otherwise redirect response to destination.
   *
   * @return RedirectResponse
   *   Response
   */
  public function duplicateCampaignAction($redirect_to_campaign = FALSE) {
    return DdActionCenterCampaignHelper::duplicateCampaignAction($redirect_to_campaign);
  }
}
