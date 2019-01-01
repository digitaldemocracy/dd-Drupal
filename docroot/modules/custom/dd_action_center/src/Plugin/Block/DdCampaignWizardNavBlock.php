<?php

namespace Drupal\dd_action_center\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\node\Entity\Node;

/**
 * Provides a 'DdCampaignWizardNavBlock' block.
 *
 * @Block(
 *  id = "dd_campaign_wizard_nav_block",
 *  admin_label = @Translation("Dd campaign wizard nav block"),
 * )
 */
class DdCampaignWizardNavBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route = \Drupal::routeMatch();
    $route_name = $route->getRouteName();
    $parameters = $route->getParameters();
    $campaign = NULL;
    $current_position = '';
    $session = \Drupal::service('session');

    // Determine campaign route info / current position.
    if (!empty($route->getParameter('node'))) {
      $campaign = $parameters->get('node');
      if ($route_name == 'entity.node.edit_form') {
        $current_position = 'add-details';
        $session->set('campaign_wizard_from_edit', TRUE);
      }
      elseif ($route_name == 'dd_action_center.dd_campaign_statewide_form') {
        $current_position = 'catch-all';
      } else {
	      $current_position = 'review-campaign';
      }

    }
    elseif (!empty($route->getParameter('node_type'))) {
      // From node.add route.
      $current_position = 'add-details';
      $session->set('campaign_wizard_from_edit', FALSE);
    }
    elseif (!empty($route->getParameter('campaign_id'))) {
      $campaign = Node::load($route->getParameter('campaign_id'));
      switch ($route_name) {
        case 'dd_action_center.dd_campaign_preview_form':
          $current_position = 'preview-campaign';
          break;

        case 'dd_action_center.dd_campaign_targets_form':
          $current_position = 'choose-targets';
          break;

        case 'dd_action_center.dd_campaign_target_actions_form':
          $current_position = 'assign-actions';
          break;

        case 'dd_action_center.dd_campaign_review_target_actions_form':
          $current_position = 'review-campaign';
          break;
      }
    }

    $from_edit = $session->get('campaign_wizard_from_edit');

    $positions = [
      'add-details' => ['parent' => 'create_campaign_landing'],
      'preview-campaign' => ['parent' => 'create_campaign_landing'],
      'choose-targets' => ['parent' => 'choose_targets_actions'],
      'assign-actions' => ['parent' => 'choose_targets_actions'],
      'catch-all' => ['parent' => 'choose_targets_actions'],
      'review-campaign' => ['parent' => 'review_campaign'],
    ];

    $steps = [
      'add-details' => 0,
      'preview-campaign' => 1,
      'choose-targets' => 2,
      'assign-actions' => 3,
      'catch-all' => 4,
      'review-campaign' => 5,
    ];

    $statewide_campaign = FALSE;
    // @todo enable once statewide campaign field in place.
    $query_statewide_campaign = \Drupal::request()->get('statewide');
    if ($query_statewide_campaign != NULL) {
      $statewide_campaign = (bool) $query_statewide_campaign;
    }
    elseif ($campaign) {
      $statewide_campaign = $campaign->get('field_is_statewide_campaign')->value;
    }

    // Create Campaign Landing Page items.
    $campaign_landing_item_parent_class = $from_edit ? 'completed-item' : 'inactive-item';
    $choose_targets_actions_item_parent_class = $from_edit ? 'completed-item' : 'inactive-item';
    $review_campaign_item_parent_class = $from_edit ? 'completed-item' : 'inactive-item';

    // Add Details.
    if ($current_position == 'add-details') {
      $campaign_landing_item_class = 'active-item';
      $campaign_landing_item_parent_class = 'active-item';
    }
    else {
      $campaign_landing_item_class = ($steps[$current_position] > 0 || $from_edit) ? 'completed-item' : 'inactive-item';
    }

    $details_link = 'Add Details/Video/Bill';
    if ($steps[$current_position] > 0 || $from_edit) {
      $details_link = Link::createFromRoute($details_link, 'entity.node.edit_form', ['node' => $campaign->id()])->toString();
    }

    $preview_campaign_link = 'Preview Campaign';
    $create_campaign_link = 'Create Campaign Landing Page';

    if ($steps[$current_position] > 0 || $from_edit) {
      $create_campaign_link = Link::createFromRoute($create_campaign_link, 'entity.node.edit_form', ['node' => $campaign->id()])->toString();
    }

    if ($steps[$current_position] > 1 || $from_edit) {
      $preview_campaign_link = Link::createFromRoute($preview_campaign_link, 'dd_action_center.dd_campaign_preview_form', ['campaign_id' => $campaign->id()])->toString();
    }

    $landing_page_items = [
      [
        '#markup' => $details_link,
      ],
      [
        '#markup' => $preview_campaign_link,
      ],
    ];

    $landing_page_items[0]['#wrapper_attributes'] = ['class' => $campaign_landing_item_class];

    // Preview Campaign.
    if ($current_position == 'preview-campaign') {
      $campaign_landing_item_class = 'active-item';
      $campaign_landing_item_parent_class = 'active-item';
    }
    else {
      $campaign_landing_item_class = ($steps[$current_position] > 0 || $from_edit) ? 'completed-item' : 'inactive-item';
    }
    $landing_page_items[1]['#wrapper_attributes'] = ['class' => $campaign_landing_item_class];

    $landing_page_item['#markup'] = $create_campaign_link;

    if ($positions[$current_position]['parent'] == 'create_campaign_landing') {
      $landing_page_item['create_campaign_landing_children']['#theme'] = 'item_list';
      $landing_page_item['create_campaign_landing_children']['#items'] = $landing_page_items;
    }

    // Choose Targets and Assign Action Items.
    $catch_all_link = $statewide_campaign ? 'Choose Actions' : 'Create Catch-All';
    $choose_targets_link = 'Choose Targets';
    $choose_targets_actions_link = $statewide_campaign ? 'Statewide Actions' : 'Choose Targets and Assign Actions';

    if ($steps[$current_position] > 2 || $from_edit) {
      $choose_targets_link = Link::createFromRoute($choose_targets_link, 'dd_action_center.dd_campaign_targets_form', ['campaign_id' => $campaign->id()])->toString();
    }

    $assign_actions_link = 'Assign Actions';

    if ($steps[$current_position] > 3 || $from_edit) {
      $assign_actions_link = Link::createFromRoute($assign_actions_link, 'dd_action_center.dd_campaign_target_actions_form', ['campaign_id' => $campaign->id()])->toString();
    }

    if (!$statewide_campaign && ($steps[$current_position] > 2 || $from_edit)) {
      $choose_targets_actions_link = Link::createFromRoute($choose_targets_actions_link, 'dd_action_center.dd_campaign_targets_form', ['campaign_id' => $campaign->id()])
        ->toString();
    }
    elseif ($statewide_campaign && ($steps[$current_position] > 4 || $from_edit)) {
      $choose_targets_actions_link = Link::createFromRoute($choose_targets_actions_link, 'dd_action_center.dd_campaign_statewide_form', ['node' => $campaign->id()])
        ->toString();
    }

    if (!$statewide_campaign) {
      $choose_targets_actions_items = [
        [
          '#markup' => $choose_targets_link,
        ],
        [
          '#markup' => $assign_actions_link,
        ],
      ];
    }

    if ($steps[$current_position] > 5 || $from_edit) {
      $catch_all_link = Link::createFromRoute($catch_all_link, 'dd_action_center.dd_campaign_statewide_form', ['node' => $campaign->id()])->toString();
    }

    $choose_targets_actions_items[] = ['#markup' => $catch_all_link];

    if (!$statewide_campaign) {
      // Choose Targets.
      if ($current_position == 'choose-targets') {
        $choose_targets_actions_item_class = 'active-item';
        $campaign_landing_item_parent_class = 'completed-item';
        $choose_targets_actions_item_parent_class = 'active-item';
      }
      else {
        $choose_targets_actions_item_class = ($steps[$current_position] > 2 || $from_edit) ? 'completed-item' : 'inactive-item';
      }
      $choose_targets_actions_items[0]['#wrapper_attributes'] = ['class' => $choose_targets_actions_item_class];

      // Assign Actions.
      if ($current_position == 'assign-actions') {
        $choose_targets_actions_item_class = 'active-item';
        $choose_targets_actions_item_parent_class = 'active-item';
        $campaign_landing_item_parent_class = 'completed-item';
      }
      else {
        $choose_targets_actions_item_class = ($steps[$current_position] > 3 || $from_edit) ? 'completed-item' : 'inactive-item';
      }
      $choose_targets_actions_items[1]['#wrapper_attributes'] = ['class' => $choose_targets_actions_item_class];
    }

    // Catch All.
    if ($current_position == 'catch-all') {
      $choose_targets_actions_item_class = 'active-item';
      $choose_targets_actions_item_parent_class = 'active-item';
      $campaign_landing_item_parent_class = 'completed-item';
    }
    else {
      $choose_targets_actions_item_class = ($steps[$current_position] > 4 || $from_edit) ? 'completed-item' : 'inactive-item';
    }
    $catch_all_index = $statewide_campaign ? 0 : 2;
    $choose_targets_actions_items[$catch_all_index]['#wrapper_attributes'] = ['class' => $choose_targets_actions_item_class];

    $choose_targets_actions_item['#markup'] = $choose_targets_actions_link;

    if ($positions[$current_position]['parent'] == 'choose_targets_actions') {
      $choose_targets_actions_item['choose_targets_actions_children']['#theme'] = 'item_list';
      $choose_targets_actions_item['choose_targets_actions_children']['#items'] = $choose_targets_actions_items;
    }

    // Review Campaign and Finish.
    $review_campaign_link = 'Review Campaign and Finish';

    if ($from_edit) {
      $review_campaign_link = Link::createFromRoute($review_campaign_link, 'dd_action_center.dd_campaign_review_target_actions_form', ['campaign_id' => $campaign->id()])
        ->toString();
    }
    $review_campaign_item = [
      [
        '#markup' => $review_campaign_link,
      ],
    ];

    // Create Campaign Landing Page items.
    if ($current_position == 'review-campaign') {
      $review_campaign_item_class = 'active-item';
      $review_campaign_item_parent_class = 'active-item';
      $choose_targets_actions_item_parent_class = 'completed-item';
      $campaign_landing_item_parent_class = 'completed-item';
    }
    else {
      $review_campaign_item_class = ($steps[$current_position] > 5 || $from_edit) ? 'completed-item' : 'inactive-item';
    }

    // Parent items classes.
    $landing_page_item['#wrapper_attributes'] = ['class' => [$campaign_landing_item_parent_class, 'parent']];
    $choose_targets_actions_item['#wrapper_attributes'] = ['class' => [$choose_targets_actions_item_parent_class, 'parent']];
    $review_campaign_item['#wrapper_attributes'] = ['class' => [$review_campaign_item_parent_class, 'parent']];

    $form['campaign_wizard_nav'] = [
      '#theme' => 'item_list',
      '#items' => [
        'landing_page' => $landing_page_item,
        'choose_targets' => $choose_targets_actions_item,
        'review_campaign' => $review_campaign_item,
      ],
    ];

    $form['save_for_later'] = [
      '#type' => 'button',
      '#value' => $this->t('Save & Finish Later'),
      '#attributes' => ['id' => 'save-finish-later-button'],
    ];

    $form['#cache'] = ['max-age' => 0];
    return $form;
  }
}
