<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\BillDiscussionSpeakerStartTime
 */

namespace Drupal\dd_bill\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_bill\Entity\DdBillDiscussion;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to provide bill discussion speaker start time.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bill_discussion_speaker_start_time")
 */
class BillDiscussionSpeakerStartTime extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options.
   * @return array
   *   Array of options.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');
    $options['did'] = array('default' => '');

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['pid'] = [
      '#title' => $this->t('Person ID'),
      '#description' => $this->t('PID such as 22, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
    ];
    $form['did'] = [
      '#title' => $this->t('BillDiscussion ID'),
      '#description' => $this->t('BillDiscussion such as 22, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['did'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $start_time = '';
    $did = '';
    $pid = '';

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
    }

    if (!empty($this->options['did'])) {
      $did = $this->tokenizeValue($this->options['did'], $values->index);
    }

    if ($did && $pid) {
      $utterance = DdBillDiscussion::getSpeakerStartUtterance($did, $pid);
      if ($utterance) {
        $start_time = $utterance->time;
      }
    }
    return $start_time;
  }
}
