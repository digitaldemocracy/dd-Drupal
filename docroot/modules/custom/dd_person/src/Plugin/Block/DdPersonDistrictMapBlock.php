<?php

namespace Drupal\dd_person\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\dd_legislator\Entity\DdTerm;

/**
 * Provides a 'DdPersonDistrictMapBlock' block.
 *
 * @Block(
 *  id = "dd_person_district_map_block",
 *  admin_label = @Translation("Person District Map Block"),
 * )
 */
class DdPersonDistrictMapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $parameters = \Drupal::routeMatch()->getParameters();
    $pid = $parameters->get('dd_person')->id();
    $person = DdPerson::load($pid);
    $build = [];
    $show_map = FALSE;

    $classifications = DdPerson::getClassificationsForPid($pid, 1);
    if ($classifications) {
      foreach ($classifications as $classification) {
        if ($classification->PersonType == 'Legislator') {
          $show_map = TRUE;
        }
      }
    }

    if ($show_map) {
      $build['dd_person_district_map_block_title'] = array(
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => 'District Map',
      );

      $build['dd_person_district_map_block'] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('mapbox', 'box', 'expanded'),
          'id' => 'mapbox_ca',
          'style' => 'width: 100%; height: 250px;',
        ),
      );

      $terms = DdTerm::getTermsForLegislatorPids(array($person->id()));
      $build['#cache'] = array('max-age' => 0);

      $build['dd_person_district_map_block']['#attached']['drupalSettings']['dd_person']['pid'] = $pid;
      $build['dd_person_district_map_block']['#attached']['drupalSettings']['dd_terms'] = $terms;
      $build['dd_person_district_map_block']['#attached']['library'][] = 'dd/dd-person-district-map';
    }
    return $build;
  }
}
