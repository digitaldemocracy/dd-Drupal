<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\HearingSpeakers
 */

namespace Drupal\dd_lobbyist\Plugin\views\field;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_organization\Entity\DdOrganizationAlignment;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to provide testimony position.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("testimony_position")
 */
class TestimonyPosition extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['oid'] = array('default' => '');
    $options['hid'] = array('default' => '');
    $options['pid'] = array('default' => '');
    $options['did'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['oid'] = [
      '#title' => $this->t('Organization ID'),
      '#description' => $this->t('OID such as 5340, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['oid'],
    ];

    $form['hid'] = [
      '#title' => $this->t('Hearing ID'),
      '#description' => $this->t('HID such as 1780, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['hid'],
    ];
    $form['pid'] = [
      '#title' => $this->t('Person ID'),
      '#description' => $this->t('PID such as 5340, supports tokens. If specified will ignore oid'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
    ];
    $form['did'] = [
      '#title' => $this->t('BillDiscussion ID'),
      '#description' => $this->t('DID such as 1780, supports tokens. If specified will ignore oid'),
      '#type' => 'textfield',
      '#default_value' => $this->options['did'],
    ];
  }

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $output = '';
    $oid = '';
    $hid = '';
    $pid = '';
    $did = '';

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);
    if (!empty($this->options['oid']) && !empty($this->options['hid'])) {
      $oid = $this->tokenizeValue($this->options['oid'], $values->index);
      $hid = $this->tokenizeValue($this->options['hid'], $values->index);
    }
    if (!empty($this->options['pid']) && !empty($this->options['did'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
      $did = $this->tokenizeValue($this->options['did'], $values->index);
    }

    if (!empty($hid) && !empty($pid) && !empty($did)) {
      // Attempt to get OID represented via pid and did.
      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CombinedRepresentations', 'cr')
        ->condition('pid', $pid)
        ->condition('hid', $hid)
        ->condition('did', $did);

      $query->join('Organizations', 'o', 'o.oid = cr.oid');
      $query->fields('o', ['oid']);

      $org = $query->execute()->fetchCol();

      if ($org) {
        $oid = $org[0];
      }
    }

    if (!empty($pid) && !empty($did)) {

      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('currentUtterance', 'cu')
        ->distinct(True)
        ->fields('cu', ['alignment'])
        ->condition('pid', $pid)
        ->condition('did', $did)
        ->condition('alignment', 'Indeteminate', '!=');
      $bill_alignment = $query->execute()->fetchCol();

      $output = '';
      if ($bill_alignment) {
        switch ($bill_alignment[0]) {
          case 'For':
            $output = 'Support';
            break;

          case 'for_if_amend':
            $output = 'Support If Amended';
            break;

          case 'Against':
            $output = 'Oppose';
            break;

          case 'against_unless_amend':
            $output = 'Oppose Unless Amended';
            break;

          case 'Neutral':
            $output = 'Neutral';
            break;
        };
      }
    }

    /*if (!empty($oid) && !empty($hid)) {

      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('OrgAlignments', 'oa')
        ->fields('oa', ['alignment'])
        ->condition('oid', $oid)
        ->condition('hid', $hid);
      $org_alignment = $query->execute()->fetchCol();

      $output = '';
      if ($org_alignment) {
        switch ($org_alignment[0]) {
          case 'For':
            $output = 'Support';
            break;

          case 'for_if_amend':
            $output = 'Support If Amended';
            break;

          case 'Against':
            $output = 'Oppose';
            break;

          case 'against_unless_amend':
            $output = 'Oppose Unless Amended';
            break;

          case 'Neutral':
            $output = 'Neutral';
            break;
        };
      }
    }*/
    return $output;
  }
}
