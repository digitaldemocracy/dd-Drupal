<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Class DdNewCampaignForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdNewCampaignForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_new_campaign_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attached']['drupalSettings']['campaign_wizard_step'] = \Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper::getCampaignWizardClass();
    $url = Url::fromRoute('node.add', ['node_type' => 'campaign'], ['query' => ['statewide' => 1]]);

    $form['description_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['statewide-targeted-campaign-description']],
      'description' => [
        '#type' => 'markup',
        '#markup' => $this->t('Description placeholder'),
      ]
    ];

    $form['statewide_campaign'] = [
      '#type' => 'link',
      '#url' => $url,
      '#attributes' => [
        'class' => ['button'],
      ],
      '#title' => $this->t('Create Statewide Campaign'),
    ];

    $url = Url::fromRoute('node.add', ['node_type' => 'campaign'], ['query' => ['statewide' => 0]]);
    $form['targeted_campaign'] = [
      '#type' => 'link',
      '#url' => $url,
      '#attributes' => [
        'class' => ['button'],
      ],
      '#title' => $this->t('Create Targeted Campaign'),
    ];

    $form['#attached']['library'][] = 'dd/campaign-admin';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}
