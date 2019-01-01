<?php

namespace Drupal\dd_action_center\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'DdActionCenterAddressBlock' block.
 *
 * @Block(
 *  id = "dd_action_center_address_lookup_block",
 *  admin_label = @Translation("Actions: Address Lookup Form"),
 * )
 */
class DdActionCenterAddressBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = [];
    $show_block = TRUE;
    $parameters = \Drupal::routeMatch()->getParameters();
    $paragraph_types = ['email_action', 'fax_action', 'phone_action', 'tweet_legislator_action', 'letter_to_editor'];

    // Always show block, previous logic would check list of actions needing targeting.
    $node = $parameters->get('node');
    /*
    if ($node) {
      $paragraphs = $parameters->get('node')->get('field_actions')->referencedEntities();
      if ($paragraphs) {
        foreach ($paragraphs as $paragraph) {
          if (in_array($paragraph->getType(), $paragraph_types)) {
            $show_block = TRUE;
            continue;
          }
        }
      }
    }
    */

    if ($show_block) {
      $form = \Drupal::formBuilder()
        ->getForm('Drupal\dd_action_center\Form\DdAddressLookupForm', ['campaign_id' => $node->id()]);

    }
    if ($node) {
      $form['#attached']['drupalSettings']['campaign_id'] = $node->id();
    }
    $form['#cache'] = ['max-age' => 0];
    return $form;
  }
}

