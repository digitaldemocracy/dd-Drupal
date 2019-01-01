<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\views\Views;

/**
 * Class DdCampaignTargetActionsForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdCampaignTargetActionsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_campaign_target_actions_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign_id = 0) {
    $form['#attached']['drupalSettings']['campaign_wizard_step'] = \Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper::getCampaignWizardClass();
    $form['#prefix'] = '<div class="page--description">To create a targeted campaign, <strong>select all the legislators you that you want to target</strong> with actions. You will have the option to group targets and assign actions, and/or create individualized actions for specific legislators.</div>';

    // For duplicated action group, get the ID and add to drupalSettings.
    $request = \Drupal::request();
    $duplicate_campaign_action_id = $request->query->get('duplicate_campaign_action_id');
    if (!empty($duplicate_campaign_action_id)) {
      $form['#attached']['drupalSettings']['duplicate_campaign_action_id'] = $duplicate_campaign_action_id;
    }

    $form_state->set('campaign_id', $campaign_id);
    $campaign_entity = Node::load($campaign_id);
    $title = t('2. Assign Actions to Selected Targets');

    $route = \Drupal::routeMatch();
    $route->getRouteObject()->setDefault('_title', $title->render());

    $form['no_targets_available'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['messages', 'messages--warning', 'hide-unassigned-warning']],
      [
        '#type' => 'item',
        '#markup' => $this->t('All targets assigned - Unassign some to create/assign new action groups'),
      ],
    ];

    $form['no_targets_selected'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['messages', 'messages--warning']],
      [
        '#type' => 'item',
        '#markup' => $this->t('Select targets above to continue.'),
      ],
    ];

    $is_statewide_campaign = $campaign_entity->field_is_statewide_campaign->value;
    $form['statewide_campaign'] = [
      '#value' => $is_statewide_campaign,
      '#type' => 'hidden',
      '#attributes' => ['id' => 'statewide_campaign'],
    ];

    // Create a campaign_action node for the form builder.
    $campaign_action_node = \Drupal::entityTypeManager()->getStorage('node')->create([
      'type' => 'campaign_action',
      'field_campaign' => [
        ['target_id' => $campaign_id],
      ],
    ]);

    $campaign_action_form = \Drupal::service('entity.manager')
      ->getFormObject('node', 'default')
      ->setEntity($campaign_action_node);

    $new_campaign_action_form = \Drupal::formBuilder()->getForm($campaign_action_form);

    // Add nav buttons.
    $back_url = \Drupal\Core\Url::fromRoute('dd_action_center.dd_campaign_targets_form', ['campaign_id' => $campaign_id]);

    $new_campaign_action_form['actions'] = [
      '#type' => '#container',
      '#attributes' => ['class' => ['form-actions', 'js-form-wrapper', 'form-wrapper']],
      'nav_buttons_wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['nav-buttons-wrapper'], 'id' => 'edit-actions'],
      ],
    ];

    $new_campaign_action_form['actions']['nav_buttons_wrapper']['back'] = [
      '#type' => 'link',
      '#url' => $back_url,
      '#attributes' => [
        'class' => ['button--back'],
        'id' => ['edit-back']
      ],
      '#title' => t('Back'),
      '#weight' => 0,
    ];

    $statewide_link = \Drupal\Core\Url::fromRoute('dd_action_center.dd_campaign_statewide_form', ['node' => $campaign_id]);

    $unassigned_target_actions = \Drupal::entityQuery('node')
      ->condition('type', 'target')
      ->condition('field_campaign.target_id', $campaign_id)
      ->notExists('field_campaign_action.target_id')
      ->count()->execute();

    if ($unassigned_target_actions) {
      $next_text = $this->t('To continue, assign actions to the @num remaining target(s)', ['@num' => $unassigned_target_actions]);
    }
    else {
      $next_text = $this->t('Are you done assigning actions to targets?');
    }

    $new_campaign_action_form['actions']['nav_buttons_wrapper']['catch_all_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['submit-catch-all-wrapper']],
      'catch_all_description' => [
        '#type' => 'item',
        '#name' => 'catch_all_description',
        '#markup' => $next_text,
      ],
    ];

    if (!$unassigned_target_actions) {
      $new_campaign_action_form['actions']['nav_buttons_wrapper']['catch_all_wrapper']['submit_button_wrapper'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['submit-wrapper']],
        'catch_all' => [
          '#type' => 'link',
          '#url' => $statewide_link,
          '#attributes' => [
            'class' => ['button'],
            'id' => 'edit-next',
          ],
          '#title' => t('Next: Create Catch-All'),
          '#weight' => 45,
        ],
      ];
    }
    $form['#suffix'] = render($new_campaign_action_form);

    // Create a hidden field to hold the checkbox values.
    $target_ids = isset($form_state->getUserInput()['target_ids']) ? $form_state->getUserInput()['target_ids'] : '';
    $form['target_ids'] = [
      '#value' => $target_ids,
      '#type' => 'hidden',
      ];

    $form['#attached']['library'][] = 'dd/campaign-admin';
    $form['#attached']['library'][] = 'dd/messages';

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
    // Unused, form uses campaign action submit form callback below.
  }

  /**
   * Save handler for Campaign Targets Action.
   *
   * @param array $form
   *   Form
   * @param FormStateInterface $form_state
   *   Form State
   */
  public function save(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Submit Form Callback for Campaign Action save.
   *
   * @param array $form
   *   Form
   * @param FormStateInterface $form_state
   *   Form State
   */
  public static function submitFormCallback(array $form, FormStateInterface $form_state) {
    $route = \Drupal::routeMatch();
    $campaign_id = $route->getParameter('campaign_id');

    $element = $form_state->getTriggeringElement();

    // Update the targets.
    $user_input = $form_state->getUserInput();

    $action_node = $form_state->getFormObject()->getEntity();
    $action_node_id = $action_node->id();

    $target_ids = $user_input['target_ids'];
    if (!empty($target_ids)) {
      $ids = explode(',', $target_ids);
      foreach ($ids as $id) {
        $target_node = Node::load($id);
        $target_node->set('field_campaign_action', [['target_id' => $action_node_id]]);
        $target_node->save();
      }
    }

    if ($element['#id'] == 'edit-field-actions-add-more-save-continue-wrapper-publish') {
      $url = \Drupal\Core\Url::fromRoute('dd_action_center.dd_campaign_target_actions_form', ['campaign_id' => $campaign_id]);
      $form_state->setRedirectUrl($url);
    }
  }

  /**
   * Validate Form Callback for Campaign Action save.
   *
   * @param array $form
   *   Form
   * @param FormStateInterface $form_state
   *   Form State
   */
  public static function validateFormCallback(array $form, FormStateInterface $form_state) {
    // Check for a target_ids value.
    $user_input = $form_state->getUserInput();
    if (!isset($user_input['target_ids']) || empty($user_input['target_ids'])) {
      $form_state->setErrorByName('target_ids', 'No target(s) selected!');
    }

    // Rewrite "Null Value not alllowed" error message.
    $errors = $form_state->getErrors();
    if ($errors) {
      if (isset($errors['field_actions'])) {
        $form_state->clearErrors();
        foreach ($errors as $key => $val) {
          if ($key == 'field_actions') {
            $form_state->setErrorByName($key, t('No Actions Selected for Action Group'));
          }
          else {
            $form_state->setErrorByName($key, $val);
          }
        }
      }
    }
  }
}
