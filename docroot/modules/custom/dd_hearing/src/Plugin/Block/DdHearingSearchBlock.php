<?php

namespace Drupal\dd_hearing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_hearing\Form\DdHearingSearchForm;

/**
 * Provides a 'DdHearingSearchBlock' block.
 *
 * @Block(
 *  id = "dd_hearing_search_block",
 *  admin_label = @Translation("Hearing Search Block"),
 * )
 */
class DdHearingSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = DdHearingSearchForm::create(\Drupal::getContainer());
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\dd_hearing\Form\DdHearingSearchForm');
    $render['form'] = $builtForm;

    return $render;
  }

}
