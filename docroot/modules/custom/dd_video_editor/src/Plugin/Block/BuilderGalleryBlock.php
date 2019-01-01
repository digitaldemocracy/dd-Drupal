<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\taxonomy\Entity\Term;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a 'Clip Gallery for Builder' Block
 *
 * @Block(
 *   id = "builder_gallery_block",
 *   admin_label = @Translation("Clip Gallery block for Builder page"),
 * )
 */
class BuilderGalleryBlock extends ClipperGalleryBlock {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return parent::generateBlockArray('builder-gallery-item', 'builder-gallery-block');
  }
}

