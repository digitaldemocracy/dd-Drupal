<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\HearingSpeakers
 */

namespace Drupal\dd_lobbyist\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_bill\Entity\DdBillVoteSummary;
use Drupal\dd_organization\Entity\DdOrganizationAlignment;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide committee action.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("committee_action")
 */
class CommitteeAction extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['bid'] = array('default' => '');
    $options['date'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['bid'] = [
      '#title' => $this->t('Bill ID'),
      '#description' => $this->t('BID such as CA_201520160AB1050, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['bid'],
    ];
    $form['date'] = [
      '#title' => $this->t('Vote Date'),
      '#description' => $this->t('Date in format like August 3, 2016, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['date'],
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
    $output = '';

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['bid']) && !empty($this->options['date'])) {
      $bid = $this->tokenizeValue($this->options['bid'], $values->index);
      $date = $this->tokenizeValue($this->options['date'], $values->index);
      $result = DdBillVoteSummary::isBillPassed($bid, $date);
      if ($result !== NULL) {
        $output = $result ? 'Pass' : 'Fail';
      }
    }
    return $output;
  }
}
