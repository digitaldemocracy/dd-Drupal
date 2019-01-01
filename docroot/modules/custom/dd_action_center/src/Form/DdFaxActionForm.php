<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;


class DdFaxActionForm implements FormInterface {
  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'dd_fax_action_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $legislator_name = '@todo $legislatorname';
    $legislator_fax = '@todo $legislator_fax' ;
    $fax_subject = '@todo popupate fax subject fom action';
    $fax_body = '@todo populate fax body from action';

    $form['#title'] = 'Send a fax to '.$legislator_name;

    $form['fax_from'] = array(
      '#type' => 'phone',
      '#title' => t('From'),
      '#size' => 120,
      '#required' => TRUE,
      '#default_value' => $legislator_fax,
    );

    $form['fax_to'] = array(
      '#markup' => '<strong>To:</strong> '.$legislator_name,
      // @todo get name and fax address from legislator lookup.',
    );

    $form['fax_subject'] = array(
      '#type' => 'textfield',
      '#title' => t('Subject'),
      '#default_value' => $fax_subject,
      '#size' => 120,
      '#required' => TRUE,
    );

    $form['fax_body'] = array(
      '#type' => 'textarea',
      '#title' => t('Body'),
      '#default_value' => $fax_body,
      '#rows' => 12,
    );

    $form['subscribe_thiscampaign'] = array(
      '#type' => 'checkbox',
      '#title' => t('Send me updates about this campaign.'),
      '#default_value' => TRUE,
      // @todo What does this do?  Should it be hidden if user is already subscribed?
    );

    $form['subscribe_all_campaigns'] = array(
      '#type' => 'checkbox',
      '#title' => t('Send me updates about all campaigns.'),
      '#default_value' => TRUE,
      // @todo What does this do?  Should it be hidden if user is already subscribed?
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send this fax'),
      // @todo some send method to send this fax.
      // @todo where to redirect them? display results in the modal? or back on the action page?
    );

    return $form;

  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('fax_subject')) < 1 ) {
      $form_state->setErrorByName('fax_subect', $this->t('You need a subject line for your fax.'));
    }
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Your fax was sent.'));
  }
}

