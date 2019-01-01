<?php

namespace Drupal\dd_fax_service\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DdFaxServiceAdminForm.
 *
 * Contains admin form functionality for the Fax Service.
 */
class DdFaxServiceAdminForm extends ConfigFormBase {

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {

    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'dd_fax_service_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dd_fax_service.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_fax_service.settings');

    $form['test_api_secret'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Fax Secret Key (test)'),
      '#default_value' => $config->get('test_api_secret'),
    ];
    $form['test_api_key'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Fax API Key (test)'),
      '#default_value' => $config->get('test_api_key'),
    ];
    $form['live_api_secret'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Fax Secret Key (live)'),
      '#default_value' => $config->get('live_api_secret'),
    ];
    $form['live_api_key'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Fax API Key (live)'),
      '#default_value' => $config->get('live_api_key'),
    ];
    $form['mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Mode'),
      '#options' => [
        'test' => $this->t('Test'),
        'live' => $this->t('Live'),
      ],
      '#default_value' => $config->get('mode'),
    ];
    $form['api_host'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Host'),
      '#default_value' => $config->get('api_host'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('dd_fax_service.settings')
      ->set('mode', $form_state->getValue('mode'))
      ->set('api_host', $form_state->getValue('api_host'))
      ->set('test_api_secret', $form_state->getValue('test_api_secret'))
      ->set('test_api_key', $form_state->getValue('test_api_key'))
      ->set('live_api_secret', $form_state->getValue('live_api_secret'))
      ->set('live_api_key', $form_state->getValue('live_api_key'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
