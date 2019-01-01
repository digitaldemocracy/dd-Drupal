<?php

namespace Drupal\dd_bill_alerts\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dd bill alert history edit forms.
 *
 * @ingroup dd_bill_alerts
 */
class DdBillAlertHistoryForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_bill_alerts\Entity\DdBillAlertHistory */
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
        drupal_set_message($this->t('Created the %label Dd bill alert history.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dd bill alert history.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_bill_alert_history.canonical', ['dd_bill_alert_history' => $entity->id()]);
  }

}
