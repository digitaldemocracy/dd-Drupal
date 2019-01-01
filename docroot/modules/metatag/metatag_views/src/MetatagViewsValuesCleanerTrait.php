<?php

namespace Drupal\metatag_views;

use Drupal\views\ViewEntityInterface;


trait MetatagViewsValuesCleanerTrait {
  /**
   * Clears the metatag form state values from illegal elements.
   *
   * @param array $metatags
   *  Array of values to submit.
   *
   * @return array
   *  Filtered metatg array.
   */
  public function clearMetatagViewsDisallowedValues($metatags) {
    // Get all legal tags.
    $tags = $this->metatagManager->sortedTags();

    // Return only common elements.
    $metatags = array_intersect_key($metatags, $tags);

    return $metatags;
  }

  public function clearMetatagViewsDisallowedTranslationValues($metatags, $langcode) {
    // Get all legal tags.
    $tags = $this->metatagManager->sortedTags();

    // Return only common elements.
    $metatags = array_intersect_key($metatags[$langcode], $tags);

    return $metatags;
  }
}