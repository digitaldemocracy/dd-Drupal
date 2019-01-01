<?php

namespace Drupal\dd_person\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_person_has_spoken")
 */
class DdPersonHasSpoken extends PrerenderList {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['pid'] = [
      '#title' => $this->t('Person ID'),
      '#description' => $this->t('PID such as 22, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
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
  public function getItems(ResultRow $values) {
    $index = 0;
    $items = array();

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
      $items[$index++]['raw'] = DdPerson::hasSpokenForPid($pid);
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function render_item($count, $item) {
    return render($item['raw']);
  }
}
