<?php

namespace Drupal\search_api_autocomplete\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\search_api_autocomplete\Annotation\AutocompleteSuggester;

use Drupal\search_api_autocomplete\AutocompleteSuggesterInterface;

class AutocompleteSuggesterManager extends DefaultPluginManager {

  /**
   * Constructs a new AutocompleteSuggesterManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/search_api_autocomplete/AutocompleteSuggester', $namespaces, $module_handler, AutocompleteSuggesterInterface::class, AutocompleteSuggester::class);

    $this->setCacheBackend($cache_backend, 'search_api_autocomplete_suggester');
    $this->alterInfo('search_api_autocomplete_suggester');
  }

}
