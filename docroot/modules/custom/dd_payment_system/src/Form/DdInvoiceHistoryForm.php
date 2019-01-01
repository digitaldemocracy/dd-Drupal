<?php

namespace Drupal\dd_payment_system\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dd invoice history edit forms.
 *
 * @ingroup dd_payment_system
 */
class DdInvoiceHistoryForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_payment_system\Entity\DdInvoiceHistory */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Dd invoice history.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dd invoice history.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_invoice_history.canonical', ['dd_invoice_history' => $entity->id()]);
  }

}
