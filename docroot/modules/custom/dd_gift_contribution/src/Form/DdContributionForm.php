<?php

namespace Drupal\dd_gift_contribution\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Contribution edit forms.
 *
 * @ingroup dd_gift_contribution
 */
class DdContributionForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_gift_contribution\Entity\DdContribution */
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
        drupal_set_message($this->t('Created the %label DD Contribution.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Contribution.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_contribution.canonical', ['dd_contribution' => $entity->id()]);
  }

}
