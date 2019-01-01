<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Class DdCampaignPreviewForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdCampaignPreviewForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_campaign_preview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign_id = 0) {
    $form_state->set('campaign_id', $campaign_id);
    $campaign = Node::load($campaign_id);

    $form['#attached']['drupalSettings']['campaign_wizard_step'] = \Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper::getCampaignWizardClass();
    $form['#prefix'] = '<div class="page--description">Go back to adjust the order of the landing page items.</div>';

    $entity_type_manager = \Drupal::service('entity_type.manager');
    $view_builder = $entity_type_manager->getViewBuilder('node');
    $node_view = $view_builder->view($campaign, 'full');

    $form['preview_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['campaign-preview-wrapper']],
    ];

    $form['preview_wrapper']['preview'] = $node_view;

    $back_url = Url::fromRoute('entity.node.edit_form', ['node' => $campaign_id]);

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

    $statewide_campaign = $campaign->get('field_is_statewide_campaign')->value;

    if ($statewide_campaign) {
      $url = Url::fromRoute('dd_action_center.dd_campaign_statewide_form', ['node' => $campaign_id]);
      $next_text = $this->t('Next: Choose Actions');
    }
    else {
      $url = Url::fromRoute('dd_action_center.dd_campaign_targets_form', ['campaign_id' => $campaign_id]);
      $next_text = $this->t('Next: Choose Targets');
    }
    $form['actions']['nav_buttons_wrapper']['submit_button_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['submit-wrapper']],
      'next' => [
        '#type' => 'link',
        '#url' => $url,
        '#attributes' => [
          'class' => ['button'],
        ],
        '#title' => $next_text,
      ],
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
