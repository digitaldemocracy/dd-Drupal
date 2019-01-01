<?php
namespace Drupal\dd_action_center\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;

class DdActionCenterSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_action_center_settings_form';
  }

  /**
   * Gets configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['dd_action_center.settings'];
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_action_center.settings');
    $form['twitter_append_url'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Append url to twitter action.'),
      '#default_value' => $config->get('action_setting.twitter_append_url'),
    ];
    return parent::buildForm($form, $form_state);
  }
  /**
   * @param array &$form 
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('dd_action_center.settings')
      ->set('action_setting.twitter_append_url',
        $form_state->getValue('twitter_append_url'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}

