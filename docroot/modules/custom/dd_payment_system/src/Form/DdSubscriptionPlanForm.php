<?php

namespace Drupal\dd_payment_system\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Subscription Plan edit forms.
 *
 * @ingroup dd_payment_system
 */
class DdSubscriptionPlanForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_payment_system\Entity\DdSubscriptionPlan */
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
        drupal_set_message($this->t('Created the %label DD Subscription Plan.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Subscription Plan.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_subscription_plan.canonical', ['dd_subscription_plan' => $entity->id()]);
  }

}
