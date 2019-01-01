<?php

namespace Drupal\metatag_views\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\MetatagManagerInterface;
use Drupal\metatag_views\MetatagViewsValuesCleanerTrait;
use Drupal\views\ViewEntityInterface;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MetatagViewsAddForm.
 *
 * @package Drupal\metatag_views\Form
 */
class MetatagViewsAddForm extends FormBase {
  use MetatagViewsOptionsSubmitTrait;
  use MetatagViewsValuesCleanerTrait;
  /**
   * Drupal\metatag\MetatagManager definition.
   *
   * @var \Drupal\metatag\MetatagManagerInterface
   */
  protected $metatagManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $viewsManager;


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
    return 'metatag_views_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get views list as options.
    $in_use = $this->metatagManager->getMetatagedViews();
    $opts = Views::getViewsAsOptions(FALSE, 'enabled', NULL, TRUE, TRUE);
    // Get only the views that do not have the metatags set yet.
    $filtered = $this->filterViewList($in_use, $opts);

    // Build/inject the Metatag form. Use defaults.
    $metatags = $this->metatagManager->getDefaultMetatags();

    $form['metatags'] = $this->metatagManager->form( $metatags, $form, [ 'view' ] );
    $form['metatags']['#title'] = t('Metatags');
    $form['metatags']['#type'] = 'fieldset';

    // Need to create that AFTER the $form['metatags'] as the whole form
    // is passed to the $metatagManager->form() which causes duplicated field.
    $form['view'] = [
      '#type' => 'select',
      '#options' => $filtered,
      '#title' => t('Choose a view'),
      '#weight' => -100,
    ];

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Submit'),
    );

    return $form;
  }

  /**
   * Filters the options array to unset the views that already have metatag
   * display extender configured.
   *
   * @param $used
   *  Views to exclude
   * @param $all
   *  Views to exclude from
   * @return mixed
   *  An associative array of views and display ids.
   */
  protected function filterViewList($used, $all) {
    foreach ($used as $view_id => $displays) {
      foreach(array_keys($displays) as $display_id) {
        $id = $view_id . ':' . $display_id;
        if(isset($all[$view_id][$id])) {
          unset($all[$view_id][$id]);
        }
      }
      if(empty($all[$view_id])) {
        unset($all[$view_id]);
      }
    }
    return $all;
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
    // Get the values
    $metatags = $form_state->getValues();
    $view_option = $form_state->getValue('view');
    // Remove the unnecessary entry from values.
    unset($metatags['view']);

    list($view_id, $display_id) = explode(':', $view_option);

    /** @var ViewEntityInterface $view */
    $view = $this->viewsManager->load($view_id);

    // Clear the unneeded elements.
    $metatags = $this->clearMetatagViewsDisallowedValues($metatags);

    // Save the display extender options and the view itself.
    $this->setMetatagDisplayExtenderValues($view, $display_id, $metatags);

    // Redirect back to the views list.
    $form_state->setRedirect($this->redirectRoute);

    $display = $view->getDisplay($display_id);

    $params = [
      '@view' => $view->get('label'),
      '@display' => $display['display_title']
    ];

    drupal_set_message($this->t('Metatags for @view : @display have been added.', $params));
  }
}
