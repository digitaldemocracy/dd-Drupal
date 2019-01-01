<?php
/**
 * @file
 * Definition of Drupal\dd_action_center\Plugin\views\field\DdPhoneLog
 */

namespace Drupal\dd_action_center\Plugin\views\field;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide Campaign Action Stats.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_action_stats")
 */
class DdActionStats extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['campaign_id'] = array('default' => '');
    $options['campaign_action_id'] = array('default' => '');
    $options['campaign_action_paragraphs_id'] = array('default' => '');
    $options['target_pid'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['campaign_id'] = [
      '#title' => $this->t('Campaign ID'),
      '#description' => $this->t('Campaign ID, supports tokens.'),
      '#type' => 'textfield',
      '#default_value' => $this->options['campaign_id'],
    ];
    $form['campaign_action_id'] = [
      '#title' => $this->t('Campaign Action ID'),
      '#description' => $this->t('Campaign Action ID, supports tokens. Supports NULL string to get statewide actions'),
      '#type' => 'textfield',
      '#default_value' => $this->options['campaign_action_id'],
    ];
    $form['campaign_action_paragraphs_id'] = [
      '#title' => $this->t('Campaign Action Paragraphs ID'),
      '#description' => $this->t('Campaign Action Paragraphs ID, supports tokens. Will restrict to only this action if specified'),
      '#type' => 'textfield',
      '#default_value' => $this->options['campaign_action_paragraphs_id'],
    ];
    $form['target_pid'] = [
      '#title' => $this->t('Target PID'),
      '#description' => $this->t('Target Person ID, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['target_pid'],
    ];

    $form['replacement_patterns'] = $form['alter']['help'];
    unset($form['replacement_patterns']['#states']);
  }

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
    $campaign_id = 0;
    $campaign_action_id = 0;
    $campaign_action_paragraphs_id = 0;
    $target_pid = 0;

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['campaign_id'])) {
      $campaign_id = $this->tokenizeValue($this->options['campaign_id'], $values->index);
    }
    if (!empty($this->options['campaign_action_id'])) {
      $campaign_action_id = $this->tokenizeValue($this->options['campaign_action_id'], $values->index);
    }
    if (!empty($this->options['campaign_action_paragraphs_id'])) {
      $campaign_action_paragraphs_id = $this->tokenizeValue($this->options['campaign_action_paragraphs_id'], $values->index);
    }
    if (!empty($this->options['target_pid'])) {
      $target_pid = $this->tokenizeValue($this->options['target_pid'], $values->index);
    }

    $entity_type_manager = \Drupal::service('entity_type.manager');
    $view_builder = $entity_type_manager->getViewBuilder('paragraph');

    if (empty($campaign_action_paragraphs_id) && !empty($campaign_action_id)) {
      $campaign_action_node = Node::load($campaign_action_id);
      $field_actions = $campaign_action_node->get('field_actions')->getValue();

      foreach ($field_actions as $field_action) {
        $paragraph_id = $field_action['target_id'];
        $paragraph = Paragraph::load($paragraph_id);
        // Check metrics on this paragraph action.
        $query = \Drupal::entityQuery('dd_action_metrics')
          ->condition('campaign_id', $campaign_id)
          ->condition('target_pid', $target_pid)
          ->condition('campaign_action_id', $campaign_action_id)
          ->condition('campaign_action_paragraphs_id', $paragraph->id());

        $action_count = $query->count()->execute();

        $node_view = $view_builder->view($paragraph, 'icon');
        $build[] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['action-icon-stat-wrapper']],
          'icon' => $node_view,
          'stat' => [
            '#type' => 'item',
            '#markup' => $action_count,
            '#name' => 'action-stat',
          ],
        ];
      }
    }
    elseif (!empty($campaign_action_paragraphs_id)) {
      $paragraph = Paragraph::load($campaign_action_paragraphs_id);
      // Check metrics on this paragraph action.
      $query = \Drupal::entityQuery('dd_action_metrics')
        ->condition('campaign_id', $campaign_id)
        ->condition('target_pid', $target_pid)
        ->condition('campaign_action_paragraphs_id', $campaign_action_paragraphs_id)
        ->notExists('campaign_action_id');

      $action_count = $query->count()->execute();
      $node_view = $view_builder->view($paragraph, 'icon_label');
      $build[] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['action-icon-stat-wrapper']],
        'icon' => $node_view,
        'stat' => [
          '#type' => 'item',
          '#markup' => ' - ' . $action_count,
          '#name' => 'action-stat',
        ],
      ];
    }

    // Return the text, so the code never thinks the value is empty.
    return ViewsRenderPipelineMarkup::create(render($build));
  }

}
