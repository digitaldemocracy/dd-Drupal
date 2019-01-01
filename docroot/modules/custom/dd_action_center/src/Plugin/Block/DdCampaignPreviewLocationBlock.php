<?php

namespace Drupal\dd_action_center\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'DdCampaignPreviewLocationBlock' block.
 *
 * @Block(
 *  id = "dd_campaign_preview_location_block",
 *  admin_label = @Translation("Dd campaign preview location block"),
 * )
 */
class DdCampaignPreviewLocationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = \Drupal::formBuilder()
      ->getForm('Drupal\dd_action_center\Form\DdCampaignPreviewLocationForm');
    return $build;
  }

}
