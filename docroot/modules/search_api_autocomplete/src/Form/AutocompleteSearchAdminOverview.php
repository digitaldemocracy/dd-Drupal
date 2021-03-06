<?php

namespace Drupal\search_api_autocomplete\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;
use Drupal\search_api\IndexInterface;
use Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch;

class AutocompleteSearchAdminOverview extends FormBase {

  /**
   * @var \Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_api_autocomplete_admin_overview';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, IndexInterface $search_api_index = NULL) {
    $form_state->set('index', $search_api_index);
    $index_id = $search_api_index->id();

    $available_suggesters = $this->getSuggestersForIndex($search_api_index);
    if (!$available_suggesters) {
      $args = [
        '@feature' => 'search_api_autocomplete',
        ':service_classes_url' => 'https://www.drupal.org/node/1254698#service-classes',
      ];
      drupal_set_message($this->t('There are currently no suggester plugins installed that support this index. To solve this problem, you can either:<ul><li>move the index to a server which supports the "@feature" feature (see the <a href=":service_classes_url">available service class</a>)</li><li>or install a module providing a new suggester plugin that supports this index</li></ul>', $args), 'error');
      if ($this->loadAutocompleteSearchByIndex($index_id)) {
        $form['description'] = array(
          '#type' => 'item',
          '#title' => $this->t('Delete autocompletion settings'),
          '#description' => $this->t("If you won't use autocompletion with this index anymore, you can delete all autocompletion settings associated with it. " .
            "This will delete all autocompletion settings on this index. Settings on other indexes won't be influenced."),
        );
        $form['button'] = [
          '#type' => 'submit',
          '#value' => $this->t('Delete autocompletion settings'),
          '#submit' => [$this, 'submitDelete'],
        ];
      }
      return $form;
    }

    $form['#tree'] = TRUE;
    /** @var \Drupal\search_api_autocomplete\Plugin\AutocompleteTypeManager $type_manager */
    $type_manager = \Drupal::service('plugin_manager.search_api_autocomplete_type');
    $types = array_map(function ($definition) use ($type_manager) {
      return $type_manager->createInstance($definition['id']);
    }, $type_manager->getDefinitions());
    $searches = $this->loadAutocompleteSearchByIndex($index_id);
    /** @var \Drupal\search_api_autocomplete\AutocompleteTypeInterface $autocomplete_type */
    $searches_by_type = [];
    $unavailables_by_type = [];
    foreach ($types as $type => $autocomplete_type) {
      $t_searches = $autocomplete_type->listSearches($search_api_index);
      foreach ($t_searches as $id => $search) {
        if (isset($searches[$id])) {
          $searches_by_type[$type][$id] = $searches[$id];
          unset($searches[$id]);
        }
        else {
          reset($available_suggesters);
          $search += [
            'id' => $id,
            'label' => $id,
            'index_id' => $index_id,
            'suggester_id' => key($available_suggesters),
            'type' => $type,
            'status' => 0,
            'options' => [],
          ];
          $search['options'] += [
            'results' => TRUE,
            'fields' => [],
          ];
          // @todo this is ugly!
          $searches_by_type[$type][$id] = SearchApiAutocompleteSearch::create($search);
        }
      }
    }
    /** @var \Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch $search */
    foreach ($searches as $id => $search) {
      $type = isset($types[$search->getType()]) ? $search->getType() : '';
      $searches_by_type[$type][$id] = $search;
      $unavailables_by_type[$type][$id] = TRUE;
    }
    /** @var \Drupal\search_api_autocomplete\AutocompleteTypeInterface $autocomplete_type */
    foreach ($types as $type => $autocomplete_type) {

      if (empty($searches_by_type[$type])) {
        continue;
      }
      if (!$type) {
        $info = [];
        $info += [
          'name' => $this->t('Unavailable search types'),
          'description' => $this->t("The modules providing these searches were disabled or uninstalled. If you won't use them anymore, you can delete their settings."),
        ];
      }
      elseif (!empty($info['unavailable'])) {
        $info['description'] .= '</p><p>' . $this->t("The searches marked with an asterisk (*) are currently not available, possibly because they were deleted. If you won't use them anymore, you can delete their settings.");
      }
      $form[$type] = [
        '#type' => 'fieldset',
        '#title' => $autocomplete_type->getLabel(),
      ];
      if ($description = $autocomplete_type->getDescription()) {
        $form[$type]['#description'] = '<p>' . $description . '</p>';
      }
      $form[$type]['searches']['#type'] = 'tableselect';
      $form[$type]['searches']['#header'] = [
        'label' => $this->t('label'),
        'operations' => $this->t('Operations'),
      ];
      $form[$type]['searches']['#empty'] = '';
      $form[$type]['searches']['#js_select'] = TRUE;
      /** @var \Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch $search */
      foreach ($searches_by_type[$type] as $id => $search) {
        $form[$type]['searches'][$id] = [
          '#type' => 'checkbox',
          '#default_value' => $search->status(),
          '#parents' => ['searches', $id],
        ];
        $unavailable = !empty($info['unavailable'][$id]);
        if ($unavailable) {
          $form[$type]['searches'][$id]['#default_value'] = FALSE;
          $form[$type]['searches'][$id]['#disabled'] = TRUE;
        }
        $form_state->set(['searches', $id], $search);
        $options = &$form[$type]['searches']['#options'][$id];
        $options['label'] = $search->label();
        if ($unavailable) {
          $options['label'] = '* ' . $options['label'];
        }
        $items = array();
        if (!$unavailable && !empty($search->status())) {
          $items[] = [
            'title' => $this->t('Edit'),
            'url' => $search->toUrl('edit-form'),
          ];
          $items[] = [
            'title' => $this->t('Delete'),
            'url' => $search->toUrl('delete-form'),
          ];
        }


        if ($items) {
          $options['operations'] = ['data' => [
            '#type' => 'operations',
            '#links' => $items,
          ]];
        }
        else {
          $options['operations'] = '';
        }
        unset($options);
      }
    }

    if (!Element::children($form)) {
      $form['message']['#markup'] = '<p>' . $this->t('There are currently no searches known for this index.') . '</p>';
    }
    else {
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
      ];
    }

    return $form;
  }

  protected function getSuggestersForIndex(IndexInterface $index) {
    /** @var \Drupal\Component\Plugin\PluginManagerInterface $manager */
    $manager = \Drupal::service('plugin_manager.search_api_autocomplete_suggester');

    $suggesters = array_map(function ($suggester_info) {
      return $suggester_info['class'];
    }, $manager->getDefinitions());
    $suggesters = array_filter($suggesters, function ($suggester_class) use ($index) {
      return $suggester_class::supportsIndex($index);
    });
    return $suggesters;
  }

  /**
   * @param string $index_id
   *   The index ID.
   *
   * @return \Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch[]
   */
  protected function loadAutocompleteSearchByIndex($index_id) {
    return \Drupal::entityTypeManager()
      ->getStorage('search_api_autocomplete_settings')
      ->loadByProperties([
        'index_id' => $index_id,
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $messages = $this->t('The settings have been saved.');
    foreach ($form_state->getValue('searches') as $id => $enabled) {
      /** @var \Drupal\search_api_autocomplete\Entity\SearchApiAutocompleteSearch $search */
      $search = $form_state->get(['searches', $id]);
      if ($search->status() != $enabled) {
        $change = TRUE;
        if (!empty($search)) {
          $options['query'] = \Drupal::destination()->getAsArray();
          $options['fragment'] = 'module-search_api_autocomplete';
          $vars[':perm_url'] = Url::fromRoute('user.admin_permissions', [], $options)->toString();
          $messages = $this->t('The settings have been saved. Please remember to set the <a href=":perm_url">permissions</a> for the newly enabled searches.', $vars);
        }
        $search->setStatus($enabled);
        $search->save();
      }
    }
    drupal_set_message(empty($change) ? $this->t('No values were changed.') : $messages);
  }

  public function submitDelete(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\search_api\IndexInterface $index */
    $index = $form_state->get('index');
    $ids = array_keys($this->loadAutocompleteSearchByIndex($index->id()));
    if ($ids) {
      $controller = \Drupal::entityTypeManager()
        ->getStorage('search_api_autocomplete_search');
      $entities = $controller->loadMultiple($ids);
      $controller->delete($entities);
      drupal_set_message($this->t('All autocompletion settings stored for this index were deleted.'));
    }
    else {
      drupal_set_message($this->t('There were no settings to delete.'), 'warning');
    }
    $form_state->setRedirectUrl($index->toUrl());
  }

}
