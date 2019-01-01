<?php

namespace Drupal\dd_person\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Combined Alignments edit forms.
 *
 * @ingroup dd_person
 */
class DdCombinedAlignmentsForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_person\Entity\DdCombinedAlignments */
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
        drupal_set_message($this->t('Created the %label DD Combined Alignments.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Combined Alignments.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_combined_alignments.canonical', ['dd_combined_alignments' => $entity->id()]);
  }

}
