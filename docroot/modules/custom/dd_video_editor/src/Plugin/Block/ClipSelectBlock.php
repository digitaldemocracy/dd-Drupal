<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a Clip Select Block
 *
 * @Block(
 *   id = "clip_select_block",
 *   admin_label = @Translation("Clip Selection Block for center content area"),
 * )
 */
class ClipSelectBlock extends ClipperGalleryBlock {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $showallclips = FALSE;
    $parameters = \Drupal::routeMatch()->getParameters();
    if ($parameters->get('member') == 1) {
      $user_roles = \Drupal::currentUser()->getRoles();
      if (in_array('administrator', $user_roles) || in_array('site_manager', $user_roles) || in_array('action_center_admin', $user_roles)) {
        $showallclips = TRUE;
      }
    }

    $block = parent::generateBlockArray('clip-select-item', 'my-clip-bank-block', FALSE, $showallclips);
    $block['#attached']['drupalSettings']['campaign']['clip_field_id']
      = $parameters->get('clip_field_id');
    if ($parameters->get('member') == 1) {
      $block['#title'] = 'Member Clip Gallery';
    }
    return $block;
  }

  /**
   * {@inheritdoc}
   */
  protected function generateClipGalleryItem($node, $row_num) {
    $item = parent::generateClipGalleryItem($node, $row_num);
    $item['source_thumbnail'] = CommonHelper::$s3url . $item['clip_id'] . '/thumbnails/large.jpg';
    return $item;
  }
}
