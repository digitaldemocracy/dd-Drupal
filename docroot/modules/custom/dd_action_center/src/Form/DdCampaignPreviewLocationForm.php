<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_base\DdBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Drupal\Core\Session\SessionManager;

/**
 * Class DdCampaignPreviewLocationForm.
 *
 * @package Drupal\dd_action_center\Form
 */
class DdCampaignPreviewLocationForm extends FormBase {
  /**
   * Symfony\Component\HttpFoundation\Session\Session definition.
   *
   * @var \Symfony\Component\HttpFoundation\Session\Session
   */
  protected $session;

  /**
   * DdCampaignPreviewLocationForm constructor.
   *
   * @param Session $session
   *   Session
   */
  public function __construct(Session $session) {
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('session')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_campaign_preview_location_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $assembly_districts = [
      '' => 'Select an Assembly District',
    ];
    $senate_districts = [
      '' => 'Select a Senate District',
    ];


    $num_assembly_districts = [
      'CA' => 80,
      'NY' => 150,
    ];

    $num_senate_districts = [
      'CA' => 40,
      'NY' => 150,
    ];

    for ($index = 1; $index < $num_assembly_districts[DdBase::getCurrentState()]; $index++) {
      $assembly_districts[$index] = 'Assembly District ' . $index;
    }

    for ($index = 1; $index < $num_senate_districts[DdBase::getCurrentState()]; $index++) {
      $senate_districts[$index] = 'Senate District ' . $index;
    }

    $enable_override = FALSE;
    $assembly_district = '';
    $senate_district = '';
    if ($enable_override = $this->session->get('campaign_preview_location_override')) {
      $assembly_district = $this->session->get('campaign_preview_location_assembly_district');
      $senate_district = $this->session->get('campaign_preview_location_senate_district');
    }

    $form['enable_override'] = [
      '#type' => 'checkbox',
      '#title' => 'Preview by District',
      '#default_value' => $enable_override,
    ];
    $form['assembly_district'] = [
      '#type' => 'select',
      '#title' => 'Select an Assembly District',
      '#options' => $assembly_districts,
      '#default_value' => $assembly_district,
    ];
    $form['senate_district'] = [
      '#type' => 'select',
      '#title' => 'Select an Senate District',
      '#options' => $senate_districts,
      '#default_value' => $senate_district,
    ];

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
    ];

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
    $this->session->set('campaign_preview_location_override', $form_state->getValue('enable_override'));
    $this->session->set('campaign_preview_location_assembly_district', $form_state->getValue('assembly_district'));
    $this->session->set('campaign_preview_location_senate_district', $form_state->getValue('senate_district'));
  }

}
