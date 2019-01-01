<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class DdLetterActionForm implements FormInterface {
  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'dd_letter_action_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $local_newspaper = '@todo: figure out $localnewspaper from address lookup';

    $form['letter_to'] = array(
      '#markup' => '<strong>To: </strong>'. $local_newspaper,
    );

    $form['letter_from'] = array(
      '#type' => 'textfield',
      '#title' => 'From',
      '#default_value' => '',
      '#size' => 120,
    );

    $form['letter_subject'] = array(
      '#type' => 'textfield',
      '#title' => 'Subject',
      '#default_value' => '',
      '#size' => 120,
    );

    $form['letter_sample'] = array(
      '#type' => 'textarea',
      '#title' => 'Sample letter',
      '#default_value' => '[We need to pull this from the letter_to_editor paragraph]',
      '#rows' => 10,
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send'),
    );

    return $form;

  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement validateForm() method.
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}