<?php

namespace Drupal\dd_legislator\Plugin\views\field;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_legislator\Entity\DdTerm;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to get Legislator Info.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_legislator_info")
 */
class DdLegislatorInfo extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');
    $options['info_display'] = array('default' => 'House');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['pid'] = [
      '#title' => $this->t('Legislator ID'),
      '#description' => $this->t('PID such as 22, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
    ];

    $form['info_display'] = [
      '#title' => $this->t('Legislator Info To Display'),
      '#description' => $this->t('What Legislator info to display'),
      '#type' => 'radios',
      '#default_value' => $this->options['info_display'],
      '#options' => [
        'House' => $this->t('House'),
        'Party' => $this->t('Party'),
        'District' => $this->t('District'),
      ],
    ];
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
    $info = '';

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
      $term = DdTerm::loadByFields([['field' => 'current_term', 'value' => 1], ['field' => 'pid', 'value' => $pid]]);
      if ($term) {
        $func = 'get' . $this->options['info_display'];
        $info = current($term)->{$func}();
      }
    }

    // Return the text, so the code never thinks the value is empty.
    return ViewsRenderPipelineMarkup::create(Xss::filterAdmin($info));
  }
}
