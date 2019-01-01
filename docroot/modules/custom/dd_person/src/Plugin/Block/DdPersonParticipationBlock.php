<?php

namespace Drupal\dd_person\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;

/**
 * Provides a 'DdPersonParticipationBlock' block.
 *
 * @Block(
 *  id = "dd_person_participation_block",
 *  admin_label = @Translation("Person Participation Block"),
 * )
 */
class DdPersonParticipationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Load person information.
    $parameters = \Drupal::routeMatch()->getParameters();
    $pid = $parameters->get('dd_person')->id();

    // Load the person's participation.
    $db = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName());
    $data = $db->select('LegAvgPercentParticipation', 'lapp')
      ->fields('lapp', ['first', 'last', 'AvgPercentParticipation']);
    $data->condition('lapp.pid', $pid);
    $data = $data->execute();
    $data = $data->fetchObject();

    $build = [];

    if ($data) {
      // Load data for the chart.
      $data = [
        'participation' => [
          'first' => $data->first,
          'last' => $data->last,
          'LegAvgPercentParticipation' => $data->AvgPercentParticipation,
        ],
      ];

      // Render blocks.
      $build['dd_person_participation_block_title'] = array(
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#attributes' => [
          'type' => 'application/json',
          'id' => 'personParticipationData',
        ],
        '#value' => json_encode($data),
      );

      $build['dd_person_participation_block'] = array(
        '#type' => 'inline_template',
        '#template' => '
      <div class="chartbox box expanded">
          <div id="wrapper">
              <div id="partSmall"></div>
              <div id="leg2S"> <p>Average Percent Participation all Hearings</p> </div>
          </div>
      </div>',
        '#context' => [],
      );
      $build['#cache'] = array('max-age' => 0);

      $build['dd_person_participation_block']['#attached']['drupalSettings']['dd_person']['pid'] = $pid;;
    }
    return $build;
  }
}
