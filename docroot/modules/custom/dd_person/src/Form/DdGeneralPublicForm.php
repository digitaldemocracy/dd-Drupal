<?php

namespace Drupal\dd_person\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD General Public edit forms.
 *
 * @ingroup dd_person
 */
class DdGeneralPublicForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_person\Entity\DdGeneralPublic */
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
        drupal_set_message($this->t('Created the %label DD General Public.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD General Public.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_general_public.canonical', ['dd_general_public' => $entity->id()]);
  }

}
