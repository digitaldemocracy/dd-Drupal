<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;



class DdEmailActionForm implements FormInterface {
  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'dd_email_action_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $emailaddress = $user->get('mail')->value;
    $legislator_name = '@todo $legislatorname';
    $legislator_emailaddress = 'legislator@needslookup.com';
    $email_subject = '@todo popupate email subject fom action';
    $email_body = '@todo populate email body from action';

    $form['#title'] = 'Send an email to $legislator_name';

    $form['email_from'] = array(
      '#type' => 'email',
      '#title' => t('From'),
      '#size' => 120,
      '#required' => TRUE,
      '#default_value' => $emailaddress,
    );

    $form['email_to'] = array(
      '#markup' => '<strong>To: </strong>' . $legislator_name .'<'.$legislator_emailaddress.'>',
      // @todo get name and email address from legislator lookup.',
    );

    $form['email_subject'] = array(
      '#type' => 'textfield',
      '#title' => t('Subject'),
      '#default_value' => $email_subject,
      // @todo get email subject from email_action paragraph.
      '#size' => 120,
      '#required' => TRUE,
    );

    $form['email_body'] = array(
      '#type' => 'textarea',
      '#title' => t('Body'),
      '#default_value' => $email_body,
      // @todo get email subject from email_action paragraph.
      '#rows' => 12,
    );

    $form['subscribe_thiscampaign'] = array(
      '#type' => 'checkbox',
      '#title' => t('Send me updates about this campaign.'),
      '#default_value' => TRUE,
      // @todo What does this do?
    );

    $form['subscribe_all_campaigns'] = array(
      '#type' => 'checkbox',
      '#title' => t('Send me updates about all campaigns.'),
      '#default_value' => TRUE,
      // @todo What does this do?
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send'),
      // @todo some send method to send this email... with an ajax response to show the result in the dialog?
    );

    return $form;

  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('email_subject')) < 1 ) {
      $form_state->setErrorByName('email_subect', $this->t('You need a subject line for your email.'));
    }
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Your email was sent.'));
  }
}