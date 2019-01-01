<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a 'Video Editor Menu' Block
 *
 * @Block(
 *   id = "video_editor_menu_block",
 *   admin_label = @Translation("Menu For Video Editor"),
 * )
 */
class VideoEditorMenuBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $menu_links = array();
    $link_options = array(
      'attributes' => array(
        'class' => array(
          'my-clip-bank',
        ),
      ),
    );
    $menu_links[] = Link::fromTextAndUrl('My Clip Bank',
      Url::fromRoute('dd_video_editor.my_clip_bank_page', ['user' => \Drupal::currentUser()->id()])
        ->setOptions($link_options))->toString();

    if (CommonHelper::isPermitted('access dd video builder')) {
      $link_options = array(
        'attributes' => array(
          'class' => array(
            'video-builder',
          ),
        ),
      );

      $menu_links[] = Link::fromTextAndUrl('Video Builder',
        Url::fromRoute('dd_video_editor.video_builder', ['user' => \Drupal::currentUser()->id()])
          ->setOptions($link_options))->toString();
    }
    $block = array(
      '#theme' => 'video-editor-menu-block',
      '#items' => $menu_links,
    );
    return $block;
  }
}

