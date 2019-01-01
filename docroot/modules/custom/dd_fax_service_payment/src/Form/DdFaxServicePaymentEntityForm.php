<?php

namespace Drupal\dd_fax_service_payment\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dd fax service payment entity edit forms.
 *
 * @ingroup dd_fax_service_payment
 */
class DdFaxServicePaymentEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity */
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
        drupal_set_message($this->t('Created the %label Dd fax service payment entity.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dd fax service payment entity.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_fax_service_payment_entity.canonical', ['dd_fax_service_payment_entity' => $entity->id()]);
  }

}
