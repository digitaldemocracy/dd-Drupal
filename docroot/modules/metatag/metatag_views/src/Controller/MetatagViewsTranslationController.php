<?php

namespace Drupal\metatag_views\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\Language;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Drupal\metatag\MetatagManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MetatagViewsTranslationController extends ControllerBase {


  /** @var EntityStorageInterface  */
  protected $viewStorage;
  protected $viewsManager;

  /** @var MetatagManagerInterface */
  protected $metatagManager;

  /**
   * The language manager.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   */
  protected $languageManager;


  /**
   * MetatagViewsTranslationController constructor.
   *
   * @param EntityStorageInterface $viewStorage
   * @param MetatagManagerInterface $metatagManager
   * @param LanguageManagerInterface $languageManager
   * @param EntityTypeManagerInterface $entity_manager
   */
  public function __construct (EntityStorageInterface $viewStorage,
                               MetatagManagerInterface $metatagManager,
                               LanguageManagerInterface $languageManager,
                               EntityTypeManagerInterface $entity_manager)
  {
    $this->viewStorage = $viewStorage;
    $this->metatagManager = $metatagManager;
    $this->languageManager = $languageManager;
    $this->viewsManager = $entity_manager->getStorage('view');
  }

  /**
   * {@inheritdoc}
   */
  public static function create (ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('view'),
      $container->get('metatag.manager'),
      $container->get('language_manager'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Language translations overview page for a views.
   *
   * @return array
   *   Page render array.
   */
  public function itemPage () {

    $view_id = \Drupal::request()->get('view_id');
    $display_id = \Drupal::request()->get('display_id');

    $languages = $this->languageManager->getLanguages();

    $original_langcode = $this->languageManager->getDefaultLanguage()->getId();
    $operations_access = TRUE;

    if (!isset($languages[$original_langcode])) {
      // If the language is not configured on the site, create a dummy language
      // object for this listing only to ensure the user gets useful info.
      $language_name = $this->languageManager->getLanguageName($original_langcode);
      $languages[$original_langcode] = new Language(array('id' => $original_langcode, 'name' => $language_name));
    }

    $page['languages'] = array(
      '#type' => 'table',
      '#header' => array($this->t('Language'), $this->t('Operations')),
    );

    $metatags = $this->metatagManager->tagsFromViewDisplay($this->viewsManager->load($view_id), $display_id);

    foreach ($languages as $language) {
      $langcode = $language->getId();

      // Prepare the language name and the operations depending on whether this
      // is the original language or not.
      if ($langcode == $original_langcode) {
        $language_name = '<strong>' . $this->t('@language (original)', array('@language' => $language->getName())) . '</strong>';

        // Build list of operations.
        $operations = array();
        $operations['edit'] = array(
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('metatag_views.metatags.edit', ['view_id' => $view_id, 'display_id' => $display_id]),
        );
      } else {
        $language_name = $language->getName();

        $operations = array();
        // If no translation exists for this language, link to add one.
        if (!array_key_exists($langcode, $metatags)) {
          $operations['add'] = array(
            'title' => $this->t('Add'),
            'url' => Url::fromRoute('metatag_views.metatags.translate_langcode', ['view_id' => $view_id, 'display_id' => $display_id, 'langcode' => $langcode]),
          );
        }
        else {
          // Otherwise, link to edit the existing translation.
          $operations['edit'] = array(
            'title' => $this->t('Edit'),
            'url' => Url::fromRoute('metatag_views.metatags.translate_langcode', ['view_id' => $view_id, 'display_id' => $display_id, 'langcode' => $langcode]),
          );

          //TODO: operations delete.
        }
      }

      $page['languages'][$langcode]['language'] = array(
        '#markup' => $language_name,
      );

      $page['languages'][$langcode]['operations'] = array(
        '#type' => 'operations',
        '#links' => $operations,
        // Even if the mapper contains multiple language codes, the source
        // configuration can still be edited.
        '#access' => ($langcode == $original_langcode) || $operations_access,
      );
    }
      return $page;
  }
}