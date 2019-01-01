<?php

namespace Drupal\dd_hearing\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\dd_legislator\Entity\DdTerm;

/**
 * Provides a 'DdHearingDistrictMapBlock' block.
 *
 * @Block(
 *  id = "dd_hearing_district_map_block",
 *  admin_label = @Translation("Hearing District Map Block"),
 * )
 */
class DdHearingDistrictMapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();
    $parameters = \Drupal::routeMatch()->getParameters();
    if ($parameters->get('dd_hearing')) {
      $hid = $parameters->get('dd_hearing')->id();

      $pids = DdHearing::getLegislatorSpeakerPidsForHearing($hid);
      if ($pids) {
        $terms = DdTerm::getTermsForLegislatorPids($pids);

        $build['dd_hearing_district_map_block_title'] = array(
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' => '<h3>District Map</h3>',
          '#attributes' => array(
            'class' => 'view-header',
          ),
        );

        $build['dd_hearing_district_map_block'] = array(
          '#type' => 'container',
          '#attributes' => array(
            'class' => array(
              'view-content',
            ),
          ),
        );

        $build['dd_hearing_district_map_block']['#children'] = array(
          '#type' => 'container',
          '#attributes' => array(
            'class' => array(
              'mapbox',
              'box',
            ),
            'id' => 'mapbox_ca',
          ),
        );
        $build['#cache'] = array('max-age' => 0);
        $build['#attributes']['class'][] = 'view-showhide';
        $build['#attributes']['class'][] = 'showhide-expanded';
        $build['dd_district_map_block']['#attached']['library'][] = 'dd/dd-district-map';
        $build['dd_district_map_block']['#attached']['drupalSettings']['dd_terms'] = $terms;
      }
    }
    return $build;
  }

}
