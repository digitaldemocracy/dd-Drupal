<?php

namespace Drupal\dd_hearing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_hearing\Entity\DdHearing;

/**
 * Provides a 'DdHearingAgendizedBillsBlock' block.
 *
 * @Block(
 *  id = "dd_hearing_agendized_bills_block",
 *  admin_label = @Translation("Hearing Agendized Bills Block"),
 * )
 */
class DdHearingAgendizedBillsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();
    $parameters = \Drupal::routeMatch()->getParameters();
    if ($parameters->get('dd_hearing')) {
      $hid = $parameters->get('dd_hearing')->id();

      $build['dd_hearing_agendized_bills_block_title'] = array(
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => '<h3>Agendized Bills</h3>',
        '#attributes' => array(
          'class' => 'view-header',
        ),
      );

      $build['dd_hearing_agendized_bills_block'] = array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'agendized-bills',
          'class' => array(
            'view-content',
          ),
        ),
      );

      $build['#cache'] = array('max-age' => 8000);
      $build['#attributes']['class'][] = 'view-showhide';
      $build['#attributes']['class'][] = 'showhide-expanded';
      $build['dd_hearing_agendized_bills_block']['#attached']['library'][] = 'dd/dd-agendized-bills';
    }
    return $build;
  }

}
