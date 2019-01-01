<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_legislator\Entity\DdGovernor;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Drupal\dd_metrics\Utility\DdCampaignVisitorHelper;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

class DdPhoneActionForm implements FormInterface {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_phone_action_form';
  }

  /**
   * {@inheritdoc}
   *
   * @param NodeInterface $node
   *   Campaign Node
   * @param int $legislator_pid
   *   Legislator PID
   * @param ParagraphInterface $paragraph
   *   Phone Action Paragraph Entity
   */
  public function buildForm(array $form, FormStateInterface $form_state, NodeInterface $node = NULL, $legislator_pid = NULL, ParagraphInterface $paragraph = NULL, $is_governor = NULL) {
    $governor = NULL;
    $legislator = NULL;
    $campaign = $node;
    $campaign_action_id = NULL;

    if (empty($campaign) || $campaign->getType() != 'campaign' || empty($paragraph) || empty($legislator_pid)) {
      return NULL;
    }

    // Determine if coming from parent form or Complete Action button.
    $user_input = $form_state->getUserInput();
    $is_complete_action = (
        isset($user_input['_triggering_form_name']) && $user_input['_triggering_element_value'] == 'Complete Action'
      ) || (
        isset($user_input['op']) && $user_input['op'] == 'Complete Action'
    );

    $form_state->set('campaign', $campaign);
    $form_state->set('paragraph', $paragraph);

    // Determine if catch-all or from campaign_action node.
    if ($paragraph->get('parent_type')->value == 'node' && $paragraph->get('parent_field_name')->value == 'field_actions') {
      $node = Node::load($paragraph->get('parent_id')->value);
      if ($node->getType() == 'campaign_action') {
        $campaign_action_id = $node->id();
        $form_state->set('campaign_action_id', $campaign_action_id);
      }
    }

    $user = DdActionCenterCampaignHelper::getVisitor();
    $token = \Drupal::token();
    $campaign_title = $campaign->getTitle();

    if ($is_governor) {
      $governor = DdGovernor::getCurrentGovernor();
      $phone = $governor->getPhone();
      $person_title = 'Governor';
      $target_pid = $governor->id();
    }
    else {
      $legislator = DdLegislator::load($legislator_pid);
      $phone = $legislator->getCapitolPhone();
      $person_title = 'Legislator';
      $target_pid = $legislator->id();
    }

    $form_state->set('target_pid', $target_pid);

    // Log action metric.
    if (!$is_complete_action) {
      \Drupal::service('dd_metrics.logger')
        ->logActionMetric($campaign->id(), $paragraph->getType(), $campaign_action_id, $paragraph->id(), $target_pid);
    }

    $field_message_label    = $paragraph->get('field_advocacy_message_label')->value;
    $field_message          = $paragraph->get('field_advocacy_message')->value;
    $field_talking_points   = $paragraph->get('field_talking_points')->value;
    $field_staff            = $paragraph->get('field_capture_staff')->value;
    $field_capture_info     = $paragraph->get('field_capture_info')->value;
    $field_position         = $paragraph->get('field_legislator_position')->value;

    $field_message = $token->replace(
      $field_message,
      [
        'user' => $user,
        'campaign' => $campaign,
      ]
    );
    $field_talking_points = $token->replace(
      $field_talking_points,
      [
        'user' => $user,
        'campaign' => $campaign,
      ]
    );

    $form['campaign_title'] = [
      '#type' => 'item',
      '#markup' => '<h3> Campaign Name: ' . $campaign_title . '</h3>',
    ];

    $form['legislator_info'] = [
      '#type' => 'container',
      '#markup' => '<h4> Call your ' . ($is_governor ? 'governor' : 'representative') . ' </h4>',
      '#attributes' => [
        'class' => ['call-legislator--info'],
      ],
    ];

    $form['legislator_info']['name'] = [
      '#markup' => '<p class = "call-legislator--name"><strong>' . ($is_governor ? $governor->getFullNameFirstLast() : $legislator->getFullNameFirstLast()) . '</strong> </p>',
    ];

    $form['legislator_info']['phone_number'] = [
      '#markup' => '<p class = "call-legislator--name"><strong>' . $phone . '</strong> </p>',
    ];

    $form['advocacy_title'] = [
      '#markup' => '<p><strong>' . $field_message_label . '</strong></p>',
    ];

    $form['phone_subject'] = [
      '#markup' => '<p>' . $field_message . '</p>',
    ];

    if ($field_talking_points) {
      $form['phone_script'] = [
        '#title' => t('Sample Script'),
        '#markup' => '<p><strong>Sample Script:</strong>' . $field_talking_points . '</p>',
      ];
    }

    $form['feedback'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['phone-legislator--feedback'],
      ],
    ];

    if ($field_staff) {
      $form['feedback']['staff_person'] = [
        '#type' => 'textarea',
        '#title' => t('Name of Staff Person who took the call'),
        '#rows' => '3',
      ];
    }

    if ($field_position) {
      $form['feedback']['position'] = [
        '#type' => 'radios',
        '#title' => $person_title . t("'s Position on this Issue"),
        '#default_value' => 'unspecified',
        '#options' => [
          'supports'    => t('Supports'),
          'opposes'     => t('Opposes'),
          'undecided'   => t('Undecided'),
          'unspecified' => t('Would not specify'),
        ],
      ];
    }

    if ($field_capture_info) {
      $form['feedback']['staff_info'] = [
        '#type' => 'textarea',
        '#title' => t('Please note any comments made by the staff person about this issue'),
        '#rows' => '5',
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Complete Action'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement validateForm() method.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $visitor_user = DdActionCenterCampaignHelper::getVisitor();
    $campaign = $form_state->get('campaign');
    $paragraph = $form_state->get('paragraph');

    $visitor_id = DdCampaignVisitorHelper::createCampaignVisitor($visitor_user, $campaign->id());

    $node = Node::create([
      'type' => 'phone_action_response',
      'title' => t('Campaign ID: @campaign_id Visitor ID: @visitor_id Action ID: @action_id Legislator PID: @legislator_pid', [
        '@campaign_id' => $campaign->id(),
        '@visitor_id' => $visitor_id,
        '@action_id' => $paragraph->id(),
        '@legislator_pid' => $form_state->get('target_pid'),
      ]),
      'field_campaign' => ['target_id' => $campaign->id()],
      'field_campaign_visitor' => ['target_id' => $visitor_id],
      'field_legislator_position' => $form_state->getValue('position'),
      'field_staff_comments' => $form_state->getValue('staff_info'),
      'field_staff_member' => $form_state->getValue('staff_person'),
      'field_legislator' => ['target_id' => $form_state->get('target_pid')],
      'field_phone_action_id' => $paragraph->id(),
    ]);

    $node->save();

    $redirect_url = Url::fromRoute('entity.node.canonical', ['node' => $campaign->id()]);
    drupal_set_message(t('Thank you!'));
    $form_state->setRedirectUrl($redirect_url);

    // Log action metric conversion.
    \Drupal::service('dd_metrics.logger')->logActionConversion($campaign->id(), $paragraph->getType(), $form_state->get('campaign_action_id'), $paragraph->id(), $form_state->get('target_pid'));

  }
}
