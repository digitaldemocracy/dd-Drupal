<?php

namespace Drupal\metatag_views\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\MetatagManagerInterface;
use Drupal\metatag\MetatagTagPluginManager;
use Drupal\metatag\MetatagToken;
use Drupal\metatag_views\MetatagViewsValuesCleanerTrait;
use Drupal\views\ViewEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MetatagViewsEditForm.
 *
 * @package Drupal\metatag_views\Form
 */
class MetatagViewsTranslationForm extends FormBase {
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

  protected $tokenService;

  protected $tagPluginManager;

  /**
   * View entity object
   *
   * @var  ViewEntityInterface
   */
  protected $view;

  public function __construct(
    MetatagManagerInterface $metatag_manager,
    EntityTypeManagerInterface $entity_manager,
    MetatagToken $token,
    MetatagTagPluginManager $tagPluginManager
  ) {
    $this->metatagManager = $metatag_manager;
    $this->viewsManager = $entity_manager->getStorage('view');
    $this->tokenService = $token;
    $this->tagPluginManager = $tagPluginManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('metatag.manager'),
      $container->get('entity_type.manager'),
      $container->get('metatag.token'),
      $container->get('plugin.manager.metatag.tag')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'metatag_views_translate_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the parameters from request.
    $view_id = \Drupal::request()->get('view_id');
    $display_id = \Drupal::request()->get('display_id');
    $langcode = \Drupal::request()->get('langcode');

    /** @var ViewEntityInterface $view */
    $this->view = $this->viewsManager->load($view_id);
    // Array representing the display settings.
    $this->display = $this->view->getDisplay($display_id);

    // Get metatags from the view entity.
    $metatags = $this->metatagManager->tagsFromViewDisplay($this->view, $display_id);

    $metatags = array_filter($metatags, function($value) {
      if(is_array($value)) {
        return count(array_filter($value)) > 0;
      }
      else {
        return $value !== '';
      }
    });
    $form['#tree'] = TRUE;
    $form['#attached']['library'][] = 'config_translation/drupal.config_translation.admin';

    $form['metatags'] = $this->form($metatags, $form, ['view'], $langcode );
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
  public function form(array $values, array $element, array $token_types = [], $langcode) {
    $translation = $values[$langcode];
    $values = $this->clearMetatagViewsDisallowedValues($values);

    // Add the outer fieldset.
    $element += [
      '#type' => 'details',
    ];

    $element += $this->tokenService->tokenBrowser($token_types);

    foreach ($values as $tag_id => $value) {
      $tag = $this->tagPluginManager->createInstance($tag_id);

      $tag->setValue($translation[$tag_id]);

      $form_element = $tag->form($element);

      $element[$tag_id] = [
        '#theme' => 'config_translation_manage_form_element',
        'source' => [
          '#type' => 'item',
          '#title' => $form_element['#title'],
          '#markup' => $value
        ],
        'translation' => $form_element,
      ];
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
    //Get langcode for object
    $langcode = \Drupal::request()->get('langcode');

    //Params for redirect
    $view_id = \Drupal::request()->get('view_id');
    $display_id = \Drupal::request()->get('display_id');

    // Get the values of metatags.
    $metatags_values = $form_state->getValue('metatags');

    $metatags = [];

    //Array of metatags translations.
    foreach($metatags_values as $key => $value) {
      $metatags[$key] = $value['translation'];
    }

    // Save the display extender options and the view itself.
    $this->setMetatagDisplayExtenderValues($this->view, $this->display['id'], [$langcode => $metatags]);

    // Redirect back to the views list.
    $form_state->setRedirect('metatag_views.metatags.translate', ['view_id' => $view_id, 'display_id' => $display_id]);

    $params = [
      '@view' => $this->view->get('label'),
      '@display' => $this->display['display_title']
    ];

    drupal_set_message($this->t('Metatags for @view : @display have been updated.', $params));
  }

}
