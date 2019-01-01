<?php

namespace Drupal\dd_person\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_person\Form\DdSpeakerSearchForm;

/**
 * Provides a 'DdSpeakerSearchBlock' block.
 *
 * @Block(
 *  id = "dd_speaker_search_block",
 *  admin_label = @Translation("Speaker Search Block"),
 * )
 */
class DdSpeakerSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = DdSpeakerSearchForm::create(\Drupal::getContainer());
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\dd_person\Form\DdSpeakerSearchForm');
    $render['form'] = $builtForm;

    return $render;
  }

}
