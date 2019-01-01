<?php
/**
 * @file
 * Definition of Drupal\dd_action_center\Plugin\views\field\DdPhoneLog
 */

namespace Drupal\dd_action_center\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_bill\Entity\DdBillDiscussion;
use Drupal\node\Entity\Node;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide Phone Log button.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_phone_log")
 */
class DdPhoneLog extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $campaign_id = $values->nid;
    $show_phone_log = FALSE;
    $output = '';

    if ($campaign_id) {
      // Check catch-all and targets.
      $campaign = Node::load($campaign_id);
      $actions = $campaign->get('field_actions')->referencedEntities();
      if ($actions) {
        foreach ($actions as $action) {
          if (in_array($action->getType(), ['phone_action', 'call_governor'])) {
            $show_phone_log = TRUE;
          }
        }
      }

      if (!$show_phone_log) {
        // Check targeted actions.
        $nids = \Drupal::entityQuery('node')
          ->condition('type', 'campaign_action')
          ->condition('field_campaign.target_id', $campaign->id())
          ->execute();

        $campaign_action_nodes = Node::loadMultiple($nids);
        if ($campaign_action_nodes) {
          foreach ($campaign_action_nodes as $campaign_action_node) {
            $actions = $campaign_action_node->get('field_actions')->referencedEntities();
            if ($actions) {
              foreach ($actions as $action) {
                if (in_array($action->getType(), ['phone_action', 'call_governor'])) {
                  $show_phone_log = TRUE;
                }
              }
            }
          }
        }
      }

      if ($show_phone_log) {
        $item = [
          '#type' => 'link',
          '#title' => t('View Phone Log'),
          '#attributes' => ['class' => ['button']],
          '#url' => Url::fromRoute('view.campaign_phone_action_log.page_2', ['arg_0' => $campaign_id]),
        ];
        $output .= $this->getRenderer()->render($item);
      }
    }
    return ViewsRenderPipelineMarkup::create($output);
  }
}
