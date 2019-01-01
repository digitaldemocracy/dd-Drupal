<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\node\Entity\Node;

/**
 * Class DdCampaignTargetsForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdCampaignTargetsForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_campaign_targets_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign_id = 0) {
    $form_state->set('campaign_id', $campaign_id);
    $form['#attached']['drupalSettings']['campaign_wizard_step'] = \Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper::getCampaignWizardClass();
    $form['#prefix'] = '<div class="page--description">To create a targeted campaign, <strong>select all the legislators you that you want to target</strong> with actions. You will have the option to group targets and assign actions, and/or create individualized actions for specific legislators.</div>';

    $campaign_entity = Node::load($campaign_id);
    $title = t('Choose Targets');

    $route = \Drupal::routeMatch();
    $route->getRouteObject()->setDefault('_title', $title->render());

    // Get list of all legislators.
    $legislators = DdLegislator::getCurrentLegislators();
    $legislator_options = [];
    foreach ($legislators as $legislator) {
      $legislator_options[$legislator->pid] = $legislator->last . ', ' . $legislator->first;
    }

    // Get list of all committees.
    $committee_options_cid = ['' => 'Select a Committee'];
    $committee_options_name = ['all' => 'Show All'];
    $committees = DdCommittee::getActiveCommittees();
    foreach ($committees as $committee) {
      $committee_options_cid[$committee->cid] = $committee->name;
      $committee_options_name[$committee->name] = $committee->name;
    }

    $target_legislator_nids = \Drupal::entityQuery('node')
      ->condition('type', 'target')
      ->condition('field_campaign.target_id', $campaign_id)
      ->condition('field_legislator.target_id', '', '!=')
      ->notExists('field_committee.target_id')
      ->execute();

    $target_committee_member_nids = \Drupal::entityQuery('node')
      ->condition('type', 'target')
      ->condition('field_campaign.target_id', $campaign_id)
      ->condition('field_legislator.target_id', '', '!=')
      ->condition('field_committee.target_id', '', '!=')
      ->execute();

    $selected_legislator_pids = [];

    if (!empty($target_legislator_nids)) {
      $nodes = Node::loadMultiple($target_legislator_nids);
      foreach ($nodes as $node) {
        $selected_legislator_pids[] = $node->field_legislator->target_id;
      }
    }

    $selected_committee_member_ids = [];
    $committee_member_options = [];

    if (!empty($target_committee_member_nids)) {
      $nodes = Node::loadMultiple($target_committee_member_nids);
      foreach ($nodes as $node) {
        $key = $node->field_legislator->target_id . ':' . $node->field_committee->target_id;
        $selected_committee_member_ids[] = $key;
        $legislator = $legislators[$node->field_legislator->target_id];
        $committee_name = $committee_options_cid[$node->field_committee->target_id];
        $committee_member_options[$key] = $legislator->last . ', ' . $legislator->first . ' (' . $committee_name . ')';
      }
    }

    $form['select_legislators'] = [
      '#type' => 'details',
      '#title' => $this->t('Choose Individual Legislators'),
      '#attributes' => [
        'class' => ['details-edit-close'],
      ],
      '#collapsible' => TRUE,
      '#open' => FALSE,
      '#description' => $this->t("Legislator targets will be geo-matched based on visitor's address"),
    ];
    $form['select_legislators']['legislators_filter_by_house'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter By House'),
      '#options' => array(
        'all' => $this->t('Show All'),
        'Assembly' => $this->t('Assembly'),
        'Senate' => $this->t('Senate'),
      ),
    ];
    $form['select_legislators']['legislators_filter_by_party'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter By Party'),
      '#options' => ['all' => 'Select a Party', 'Democrat' => 'Democrat', 'Republican' => 'Republican'],
    ];

    $form['select_legislators']['legislators'] = [
      '#type' => 'multiselect',
      '#options' => $legislator_options,
      '#validated' => TRUE,
      '#default_value' => $selected_legislator_pids,
      '#available' => [
        '#description' => t('Select a name at the right and click the Add button to move to Selected Targets'),
      ],
    ];

    $form['select_committee_members'] = [
      '#type' => 'details',
      '#attributes' => [
        'class' => ['details-edit-close'],
      ],
      '#title' => $this->t('Choose Members by Committee'),
      '#collapsible' => TRUE,
      '#open' => FALSE,
      '#description' => $this->t("Legislator targets will be geo-matched based on visitor's address"),
    ];
    $form['select_committee_members']['committee_members_filter_by_house'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter Committees By House'),
      '#options' => array(
        'all' => $this->t('Show All'),
        'Assembly' => $this->t('Assembly'),
        'Senate' => $this->t('Senate'),
      ),
    ];
    $form['select_committee_members']['committee_members_filter_by_committee'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter By Committee'),
      '#options' => $committee_options_cid,
    ];

    $form['select_committee_members']['committee_members'] = [
      '#type' => 'multiselect',
      '#options' => $committee_member_options,
      '#validated' => TRUE,
      '#default_value' => $selected_committee_member_ids,
    ];

    $back_url = Url::fromRoute('dd_action_center.dd_campaign_preview_form', ['campaign_id' => $campaign_id]);
    $form['actions'] = [
      '#type' => '#container',
      '#attributes' => ['class' => ['form-actions', 'js-form-wrapper', 'form-wrapper']],
      'nav_buttons_wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['nav-buttons-wrapper'], 'id' => 'edit-actions'],
      ],
    ];

    $form['actions']['nav_buttons_wrapper']['back'] = [
      '#type' => 'link',
      '#url' => $back_url,
      '#attributes' => [
        'class' => ['button'],
      ],
      '#title' => $this->t('Back'),
    ];

    $form['actions']['nav_buttons_wrapper']['submit_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['submit-wrapper']],
      '#weight' => 50,
    ];

    $form['actions']['nav_buttons_wrapper']['submit_wrapper']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next: Assign Actions To Targets'),
    ];

    $form['#attached']['library'][] = 'dd/campaign-admin';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $campaign_id = $form_state->get('campaign_id');

    if (!$campaign_id) {
      $form_state->setErrorByName('legislators', 'Error creating targets: missing campaign_id');
    }
    else {
      $target_nids = \Drupal::entityQuery('node')
        ->condition('type', 'target')
        ->condition('field_campaign.target_id', $campaign_id)
        ->execute();

      $saved_nids = [];

      $legislators = $form_state->getValue('legislators');
      $committee_members = $form_state->getValue('committee_members');

      // Save selected legislators.
      if ($legislators) {
        foreach ($legislators as $legislator_id) {
          // Check if node exists already.
          $nids = \Drupal::entityQuery('node')
            ->condition('type', 'target')
            ->condition('field_campaign.target_id', $campaign_id)
            ->condition('field_legislator.target_id', $legislator_id)
            ->execute();

          // Create node if doesn't exist.
          if (empty($nids)) {
            $node = Node::create([
              'type' => 'target',
              'title' => t('Campaign @campaign_id: Legislator @id', [
                '@campaign_id' => $campaign_id,
                '@id' => $legislator_id,
              ]),
              'field_campaign' => [
                'target_id' => $campaign_id,
              ],
              'field_legislator' => [
                'target_id' => $legislator_id,
              ],
            ]);

            $node->save();
            $saved_nids[] = $node->id();
          }
          else {
            $saved_nids[] = reset($nids);
          }
        }
      }

      // Save selected committees.
      if ($committee_members) {
        foreach ($committee_members as $pid_cid) {
          list ($pid, $cid) = explode(':', $pid_cid);
          $nids = \Drupal::entityQuery('node')
            ->condition('type', 'target')
            ->condition('field_campaign.target_id', $campaign_id)
            ->condition('field_committee.target_id', $cid)
            ->condition('field_legislator.target_id', $pid)
            ->execute();

          // Create node if doesn't exist.
          if (empty($nids)) {
            $node = Node::create([
              'type' => 'target',
              'title' => t('Campaign @campaign_id: Committee @cid Legislator @pid', [
                '@campaign_id' => $campaign_id,
                '@cid' => $cid,
                '@pid' => $pid,
              ]),
              'field_campaign' => [
                'target_id' => $form_state->get('campaign_id'),
              ],
              'field_committee' => [
                'target_id' => $cid,
              ],
              'field_legislator' => [
                'target_id' => $pid,
              ],
            ]);

            $node->save();
            $saved_nids[] = $node->id();
          }
          else {
            $saved_nids[] = reset($nids);
          }
        }
      }

      // Delete nodes removed from selections.
      $nids_to_delete = array_diff($target_nids, $saved_nids);

      // @todo figure out what to do about orphaned actions before deleting.
      $storage_handler = \Drupal::entityTypeManager()->getStorage('node');
      $entities = $storage_handler->loadMultiple($nids_to_delete);
      $storage_handler->delete($entities);
    }

    $element = $form_state->getTriggeringElement();

    if ($element['#id'] == 'edit-save-for-later') {
      $url = \Drupal\Core\Url::fromRoute('dd_account_dashboard.campaigns', ['user' => \Drupal::currentUser()->id()]);
    }
    else {
      // Redirect to Action Targets form.
      $url = \Drupal\Core\Url::fromRoute('dd_action_center.dd_campaign_target_actions_form', ['campaign_id' => $form_state->get('campaign_id')]);
    }
    $form_state->setRedirectUrl($url);
  }

}
