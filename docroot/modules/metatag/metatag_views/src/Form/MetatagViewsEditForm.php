<?php

namespace Drupal\metatag_views\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\MetatagManagerInterface;
use Drupal\metatag_views\MetatagViewsValuesCleanerTrait;
use Drupal\views\ViewEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MetatagViewsEditForm.
 *
 * @package Drupal\metatag_views\Form
 */
class MetatagViewsEditForm extends FormBase {
  use MetatagViewsOptionsSubmitTrait;
  use MetatagViewsValuesCleanerTrait;

  /**
   * Drupal\metatag\MetatagManager definition.
   *
   * @var \Drupal\metatag\MetatagManager
   */
  protected $metatagManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $viewsManager;

  /**
   * Array of display settings as returned from getDisplay of ViewEntityInterface
   *
   * @var  array
   */
  protected $display;

  /**
   * View entity object
   *
   * @var  ViewEntityInterface
   */
  protected $view;

  public function __construct(
    MetatagManagerInterface $metatag_manager,
    EntityTypeManagerInterface $entity_manager
  ) {
    $this->metatagManager = $metatag_manager;
    $this->viewsManager = $entity_manager->getStorage('view');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('metatag.manager'),
      $container->get('entity_type.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metatag_views_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the parameters from request.
    $view_id = \Drupal::request()->get('view_id');
    $display_id = \Drupal::request()->get('display_id');

    $metatags = [];

    /** @var ViewEntityInterface $view */
    $this->view = $this->viewsManager->load($view_id);
    // Array representing the display settings.
    $this->display = $this->view->getDisplay($display_id);

    // Get metatags from the view entity.
    $metatags = $this->metatagManager->tagsFromViewDisplay($this->view, $display_id);

    $form['metatags'] = $this->metatagManager->form( $metatags, $form, ['view'] );
    $form['metatags']['#title'] = t('Metatags');
    $form['metatags']['#type'] = 'fieldset';

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $values, array $element, array $token_types = [], array $included_groups = NULL, array $included_tags = NULL) {

    // Add the outer fieldset.
    $element += [
      '#type' => 'details',
    ];

    $element += $this->tokenService->tokenBrowser($token_types);

    $groups_and_tags = $this->sortedGroupsWithTags();

    $first = TRUE;
    foreach ($groups_and_tags as $group_id => $group) {
      // Only act on groups that have tags and are in the list of included
      // groups (unless that list is null).
      if (isset($group['tags']) && (is_null($included_groups) || in_array($group_id, $included_groups))) {
        // Create the fieldset.
        $element[$group_id]['#type'] = 'details';
        $element[$group_id]['#title'] = $group['label'];
        $element[$group_id]['#description'] = $group['description'];
        $element[$group_id]['#open'] = $first;
        $first = FALSE;

        foreach ($group['tags'] as $tag_id => $tag) {
          // Only act on tags in the included tags list, unless that is null.
          if (is_null($included_tags) || in_array($tag_id, $included_tags)) {
            // Make an instance of the tag.
            $tag = $this->tagPluginManager->createInstance($tag_id);

            // Set the value to the stored value, if any.
            $tag_value = isset($values[$tag_id]) ? $values[$tag_id] : NULL;
            $tag->setValue($tag_value);

            // Create the bit of form for this tag.
            $element[$group_id][$tag_id] = $tag->form($element);
          }
        }
      }
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the values.
    $metatags = $form_state->getValues();

    // Clear the unneeded elements.
    $metatags = $this->clearMetatagViewsDisallowedValues($metatags);

    // Save the display extender options and the view itself.
    $this->setMetatagDisplayExtenderValues($this->view, $this->display['id'], $metatags);

    // Redirect back to the views list.
    $form_state->setRedirect($this->redirectRoute);

    $params = [
      '@view' => $this->view->get('label'),
      '@display' => $this->display['display_title']
    ];

    drupal_set_message($this->t('Metatags for @view : @display have been updated.', $params));
  }

}
