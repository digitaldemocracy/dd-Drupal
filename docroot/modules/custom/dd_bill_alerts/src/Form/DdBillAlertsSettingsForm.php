<?php
namespace Drupal\dd_bill_alerts\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;

class DdBillAlertsSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_bill_alerts_set_form';
  }

  /**
   * Gets configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['dd_bill_alerts.settings'];
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_bill_alerts.settings');
    $form['lasttouched'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Change seek time of cron for dd commentary alerts.'),
      '#default_value' => $config->get('datetime.back_date_text'),
    ];
    return parent::buildForm($form, $form_state);
  }
  /**
   * @param array &$form 
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('dd_bill_alerts.settings')
      ->set('datetime.back_date_text', $form_state->getValue('lasttouched'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}

