<?php
/**
 * @file
 * Definition of Drupal\dd_lobbyist\Plugin\views\field\TestifiedFor
 */

namespace Drupal\dd_lobbyist\Plugin\views\field;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_lobbyist\Entity\DdCombinedRepresentations;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to provide testified for.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("testified_for")
 */
class TestifiedFor extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');
    $options['hid'] = array('default' => '');
    $options['did'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['pid'] = [
      '#title' => $this->t('Person ID'),
      '#description' => $this->t('PID such as 5340, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
    ];
    $form['hid'] = [
      '#title' => $this->t('Hearing ID'),
      '#description' => $this->t('HID such as 1780, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['hid'],
    ];
    $form['did'] = [
      '#title' => $this->t('BillDiscussion ID'),
      '#description' => $this->t('DID such as 1780, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['did'],
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
    $pid = '';
    $hid = '';
    $did = '';

    if (!empty($this->options['pid']) && !empty($this->options['hid']) && !empty($this->options['did'])) {
      $pid = $this->tokenizeValue($this->options['pid']);
      $hid = $this->tokenizeValue($this->options['hid']);
      $did = $this->tokenizeValue($this->options['did']);
    }

    if (!empty($pid) && !empty($hid) && !empty($did)) {
      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CombinedRepresentations', 'cr')
        ->condition('pid', $pid)
        ->condition('hid', $hid)
        ->condition('did', $did);

      $query->join('Organizations', 'o', 'o.oid = cr.oid');
      $query->fields('o', ['name']);

      $org = $query->execute()->fetchCol();

      if ($org) {
        $output = implode(',', $org);
      }
    }
    return $output;
  }
}
