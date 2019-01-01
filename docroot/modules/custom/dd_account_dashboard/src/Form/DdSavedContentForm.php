<?php

namespace Drupal\dd_account_dashboard\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Saved Content edit forms.
 *
 * @ingroup dd_account_dashboard
 */
class DdSavedContentForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_account_dashboard\Entity\DdSavedContent */
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
        drupal_set_message($this->t('Created the %label DD Saved Content.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Saved Content.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_saved_content.canonical', ['dd_saved_content' => $entity->id()]);
  }

}
