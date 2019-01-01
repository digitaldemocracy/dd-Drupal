<?php

namespace Drupal\dd_legislator\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Legislative Staff edit forms.
 *
 * @ingroup dd_legislator
 */
class DdLegislativeStaffForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_legislator\Entity\DdLegislativeStaff */
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
        drupal_set_message($this->t('Created the %label DD Legislative Staff.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Legislative Staff.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_legislative_staff.canonical', ['dd_legislative_staff' => $entity->id()]);
  }

}
