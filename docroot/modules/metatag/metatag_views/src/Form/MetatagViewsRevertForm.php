<?php

namespace Drupal\metatag_views\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\views\ViewEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a confirmation form for deleting mymodule data.
 */
class MetatagViewsRevertForm extends ConfirmFormBase {
  use MetatagViewsOptionsSubmitTrait;
  /**
   * Entity manager for views entities.
   *
   * @var EntityTypeManagerInterface
   */
  protected $viewsManager;

  protected $view;
  protected $display;

  public function __construct(
    EntityTypeManagerInterface $entity_manager
  ) {
    $this->viewsManager = $entity_manager->getStorage('view');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metatag_views_revert_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to revert metatags for @view_name : @display_name?',
      [ '@view_name' => $this->view->label(),
        '@display_name' => $this->display['display_title']
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('metatag_views.metatags.list');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('This reverts the override metatags for a selected view. This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Revert');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Forget it');
  }

  /**
   * {@inheritdoc}
   *
   * @param int $id
   *   (optional) The ID of the item to be deleted.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $view_id = NULL, $display_id = NUL) {
    /** @var ViewEntityInterface view */
    $this->view = $this->viewsManager->load($view_id);
    /** @var array display */
    $this->display = $this->view->getDisplay($display_id);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Call set function in a reset mode (explicitly set the third argument to NULL).
    $this->setMetatagDisplayExtenderValues($this->view, $this->display['id'], NULL);

    // Redirect back to the views list.
    $form_state->setRedirect($this->redirectRoute);

    drupal_set_message($this->t('Reverted metatags for @view_name : @display_name',
      [ '@view_name' => $this->view->label(),
        '@display_name' => $this->display['display_title']
      ]));
  }

}
