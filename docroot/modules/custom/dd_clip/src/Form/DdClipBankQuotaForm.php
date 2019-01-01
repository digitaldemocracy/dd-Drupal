<?php

namespace Drupal\dd_clip\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for DD Clip Bank Quota edit forms.
 *
 * @ingroup dd_clip
 */
class DdClipBankQuotaForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dd_clip\Entity\DdClipBankQuota */
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
        drupal_set_message($this->t('Created the %label DD Clip Bank Quota.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label DD Clip Bank Quota.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dd_clip_bank_quota.canonical', ['dd_clip_bank_quota' => $entity->id()]);
  }

}
