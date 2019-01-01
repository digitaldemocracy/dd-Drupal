<?php

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\node\Form\NodeDeleteForm;

class DdCampaignDeleteForm extends NodeDeleteForm {
  /**
   * Override delete handler to update is_deleted flag.
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var Node $entity */
    $entity = $this->getEntity();
    $entity->set('field_is_deleted', 1);
    $entity->setPublished(FALSE);
    $entity->save();

    $form_state->setRedirectUrl($this->getRedirectUrl());
    drupal_set_message($this->getDeletionMessage());
    $this->logDeletionMessage();
  }
}
