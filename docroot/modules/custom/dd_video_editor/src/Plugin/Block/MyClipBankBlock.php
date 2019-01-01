<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\taxonomy\Entity\Term;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a 'My Clip Bank' Block
 *
 * @Block(
 *   id = "my_clip_bank_block",
 *   admin_label = @Translation("My Clip Bank Block for center content area"),
 * )
 */
class MyClipBankBlock extends ClipperGalleryBlock {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return parent::generateBlockArray('my-clip-bank-item', 'my-clip-bank-block');
  }

  /**
   * {@inheritdoc}
   */
  protected function generateClipGalleryItem($node, $row_num) {
    $item = parent::generateClipGalleryItem($node, $row_num);
    $item['source_thumbnail'] = 
      CommonHelper::$s3url . $item['clip_id'] . '/thumbnails/large.jpg';
    return $item;
  }
}

