<?php

namespace Drupal\dd_action_center\Form\DdCampaignWizard;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Class DdCampaignReviewTargetActionsForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdCampaignReviewTargetActionsForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_campaign_review_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $campaign_id = 0) {
    $form_state->set('campaign_id', $campaign_id);
    $campaign_entity = Node::load($campaign_id);
    $is_statewide_campaign = $campaign_entity->get('field_is_statewide_campaign')->value;
    $title = $is_statewide_campaign ? t('Review Actions') : t('Review Targets and Actions');

    $route = \Drupal::routeMatch();
    $route->getRouteObject()->setDefault('_title', $title->render());

    $back_url = Url::fromRoute('dd_action_center.dd_campaign_statewide_form', ['node' => $campaign_id]);

    $form['#attached']['drupalSettings']['campaign_wizard_step'] = \Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper::getCampaignWizardClass();
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
        'id' => ['edit-back'],
        'class' => ['button--back'],
      ],
      '#title' => $this->t('Back'),
    ];

    $view_url = Url::fromRoute('entity.node.canonical', ['node' => $campaign_id]);

    $form['actions']['nav_buttons_wrapper']['submit_button_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['submit-wrapper']],
      'link' => [
        '#type' => 'link',
        '#url' => $view_url,
        '#attributes' => [
          'id' => ['edit-next'],
          'class' => ['button'],
        ],
        '#title' => $this->t('View Campaign'),
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
