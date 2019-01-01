<?php

namespace Drupal\dd_fax_service\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dd fax service history edit forms.
 *
 * @ingroup dd_fax_service
 */
class DdFaxServiceHistoryForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_fax_service\Entity\DdFaxServiceHistory */
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
        drupal_set_message($this->t('Created the %label Dd fax service history.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dd fax service history.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_fax_service_history.canonical', ['dd_fax_service_history' => $entity->id()]);
  }

}
