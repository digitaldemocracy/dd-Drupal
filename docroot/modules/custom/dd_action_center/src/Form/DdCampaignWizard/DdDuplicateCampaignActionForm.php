<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_action_center\Controller\DdActionCenterController;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;

class DdDuplicateCampaignActionForm implements FormInterface {
  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'dd_duplicate_campaign_action_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign_id = NULL, $target_id = NULL, $campaign_action_id = NULL) {
    $form['duplicate_existing_group_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['duplicate-existing-group-wrapper']],
      'campaign_action_id' => [
        '#type' => 'hidden',
        '#value' => $campaign_action_id,
      ],
      'target_id' => [
        '#type' => 'hidden',
        '#value' => $target_id,
      ],
      'name' => [
        '#type' => 'textfield',
        '#title' => t('Name the new group name for reference'),
        '#required' => TRUE,
      ],
      'duplicate_action_group_assign_button' => [
        '#type' => 'submit',
        '#value' => t('Duplicate Group & Assign'),
      ],
    ];

    return $form;

  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $request = \Drupal::request();
    $request->query->set('campaign_action_id', $form_state->getValue('campaign_action_id'));
    $request->query->set('target_ids', $form_state->getValue('target_id'));
    $request->query->set('name', $form_state->getValue('name'));

    $form_state->setResponse(DdActionCenterCampaignHelper::duplicateCampaignAction());
  }
}
