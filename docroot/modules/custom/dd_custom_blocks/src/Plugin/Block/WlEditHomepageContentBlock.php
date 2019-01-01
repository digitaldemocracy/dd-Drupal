<?php

namespace Drupal\dd_custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Edit Homepage Content Block.
 *
 * @Block(
 *   id = "edit_hompepage_content_block",
 *   admin_label = @Translation("Edit Homepage Content block"),
 *   category = @Translation("DD Custom Blocks"),
 * )
 */
class WlEditHomepageContentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#markup' => '<p><a href="/wl-homepage-text">[edit homepage content]</a></p>',
    );
  }

}
