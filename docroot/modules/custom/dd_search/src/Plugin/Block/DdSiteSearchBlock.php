<?php

namespace Drupal\dd_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'DdSiteSearch' block.
 *
 * @Block(
 *  id = "dd_site_search",
 *  admin_label = @Translation("Dd Site Search Block"),
 * )
 */
class DdSiteSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = \Drupal::formBuilder()->getForm('Drupal\dd_search\Form\DdSearchForm');
    $build['#attached']['library'][] = 'dd/dd-autocomplete';

    return $build;
  }
}
