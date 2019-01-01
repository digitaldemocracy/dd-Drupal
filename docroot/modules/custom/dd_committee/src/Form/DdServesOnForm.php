<?php

namespace Drupal\dd_committee\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD ServesOn edit forms.
 *
 * @ingroup dd_committee
 */
class DdServesOnForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_committee\Entity\DdServesOn */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label DD ServesOn.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD ServesOn.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_serves_on.canonical', ['dd_serves_on' => $entity->id()]);
  }

}
