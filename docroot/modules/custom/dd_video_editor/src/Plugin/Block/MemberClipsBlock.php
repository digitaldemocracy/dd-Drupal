<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\taxonomy\Entity\Term;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a 'Member Clips' Block
 *
 * @Block(
 *   id = "member_clips_block",
 *   admin_label = @Translation("Member Clips Block for Whitelabel"),
 * )
 */
class MemberClipsBlock extends ClipperGalleryBlock {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::generateBlockArray('my-clip-bank-item', 'my-clip-bank-block', TRUE, TRUE);
    $build['#title'] = 'Member Clips';
    return $build;
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

