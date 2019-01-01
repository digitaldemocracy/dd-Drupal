<?php

namespace Drupal\dd_admin\Form;

use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_admin\DdAdmin;
use Drupal\dd_base\DdBase;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\multiselect\Element\Multiselect;

/**
 * Class DdAdminContentSettings.
 *
 * @package Drupal\dd_admin\Form
 */
class DdAdminContentSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      DdAdmin::getDdAdminContentSettingsName(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_admin_content_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(DdAdmin::getDdAdminContentSettingsName());
    $off_anon_auth_options = array(
      'off' => $this->t('Off'),
      'anonymous' => $this->t('All Visitors'),
      'authenticated' => $this->t('Logged In Users'),
    );

    $form['visibility_action_center'] = [
      '#type' => 'select',
      '#title' => $this->t('Action Center'),
      '#description' => $this->t('Visibility of Action Center'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_action_center'),
    ];
    $form['visibility_behest_payments'] = [
      '#type' => 'select',
      '#title' => $this->t('Behest Payments'),
      '#description' => $this->t('Visibility of Behest Payments'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_behest_payments'),
    ];
    $form['visibility_bills'] = [
      '#type' => 'select',
      '#title' => $this->t('Bills'),
      '#description' => $this->t('Visibility of Bills'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_bills'),
    ];
    $form['visibility_contributions'] = [
      '#type' => 'select',
      '#title' => $this->t('Contributions'),
      '#description' => $this->t('Visibility of Contributions'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_contributions'),
    ];
    $form['visibility_gifts'] = [
      '#type' => 'select',
      '#title' => $this->t('Gifts'),
      '#description' => $this->t('Visibility of Gifts'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_gifts'),
    ];
    $form['visibility_hearings'] = [
      '#type' => 'select',
      '#title' => $this->t('Hearings'),
      '#description' => $this->t('Visibility of Hearings'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_hearings'),
    ];
    // @todo determine what actions for lobbyists.
    $form['visibility_lobbyists'] = [
      '#type' => 'select',
      '#title' => $this->t('Lobbyists'),
      '#description' => $this->t('Visibility of Lobbyists'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_lobbyists'),
    ];
    $form['visibility_organizations'] = [
      '#type' => 'select',
      '#title' => $this->t('Organizations'),
      '#description' => $this->t('Visibility of Organizations'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_organizations'),
    ];
    $form['visibility_speakers'] = [
      '#type' => 'select',
      '#title' => $this->t('Speakers'),
      '#description' => $this->t('Visibility of Speakers'),
      '#options' => $off_anon_auth_options,
      '#size' => 1,
      '#default_value' => $config->get('visibility_speakers'),
    ];

    $committeees_include_exclude_options = [ 0 => t('Exclude'), 1 => t('Include') ];

    $form['committees_include_exclude'] = [
      '#type' => 'radios',
      '#title' => $this->t('Include/Exclude Committees'),
      '#description' => $this->t('Exclude or include only committees below. If no committees are selected, all will be shown.'),
      '#options' => $committeees_include_exclude_options,
      '#default_value' => $config->get('committees_include_exclude') ? $config->get('committees_include_exclude') : 0,
    ];

    $committees_options = DdCommittee::buildCommitteeOptions();

    $form['committee_cn_ids'] = [
      '#type' => 'multiselect',
      '#title' => $this->t('Committees'),
      '#description' => $this->t('Committees to exclude/include'),
      '#options' => $committees_options,
      '#default_value' => $config->get('committee_cn_ids'),
    ];
    $form['hearings_include_exclude'] = [
      '#type' => 'radios',
      '#title' => $this->t('Include/Exclude Hearings'),
      '#description' => $this->t('Exclude or include only hearings below. If no hearings are selected, all will be shown.'),
      '#options' => $committeees_include_exclude_options,
      '#default_value' => $config->get('hearings_include_exclude') ? $config->get('hearings_include_exclude') : 0,
    ];

    $hids = $config->get('hearing_hids');
    $hearing_entities = [];
    if ($hids) {
      $hearing_entities = DdHearing::loadMultiple($hids);
      $hearing_default_value = EntityAutocomplete::getEntityLabels($hearing_entities);
    }

    // @todo figure out how to allow more than maxlength, better UI.
    $form['hearing_hids'] = array(
      '#type' => 'entity_autocomplete',
      '#target_type' => 'dd_hearing',
      '#title' => $this->t('Hearings'),
      '#description' => $this->t('Hearings to exclude/include. Enter a Hearing ID, M-D-Y, or phrase to search.'),
      '#tags' => TRUE,
      '#chosen' => TRUE,
      '#maxlength' => 1024,

      '#default_value' => $hearing_entities,
    );

    return parent::buildForm($form, $form_state);
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
    $config = $this->configFactory->getEditable(DdAdmin::getDdAdminContentSettingsName());
    $form_keys = [
      'visibility_action_center',
      'visibility_behest_payments',
      'visibility_bill_details',
      'visibility_bills',
      'visibility_contributions',
      'visibility_gifts',
      'visibility_hearings',
      'visibility_lobbyists',
      'visibility_organizations',
      'visibility_speakers',
      'committees_include_exclude',
      'committee_cn_ids',
      'hearings_include_exclude',
      ];
    foreach ($form_keys as $form_key) {
      $config->set($form_key, $form_state->getValue($form_key));
    }

    if ($hids_array = $form_state->getValue('hearing_hids')) {
      $values = [];
      foreach ($hids_array as $hid_array) {
        $values[] = $hid_array['target_id'];
      }
      $config->set('hearing_hids', $values);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
