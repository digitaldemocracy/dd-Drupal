<?php
namespace Drupal\dd_video_alert\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;

class DDVideoAlertSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_alert_set_form';
  }

  /**
   * Gets configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['dd_video_alert.settings'];
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_video_alert.settings');
    $form['lasttouched'] = [ 
      '#type' => 'textfield',
      '#title' => $this->t('Define the last touched settings of dd video alerts.'),
      '#default_value' => $config->get('default_lasttouched'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * @param array &$form 
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('dd_video_alert.settings')
      ->set('default_lasttouched', $form_state->getValue('lasttouched'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
