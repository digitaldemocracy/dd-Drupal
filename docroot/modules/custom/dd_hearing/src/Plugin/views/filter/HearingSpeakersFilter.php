<?php

namespace Drupal\dd_hearing\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Filter BillDiscussions based on speaker PID.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("hearing_speakers")
 */
class HearingSpeakersFilter extends FilterPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['pid_argument'] = array('default' => '');
    $options['hid_argument'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['hid_argument'] = [
      '#title' => $this->t('Hearing ID Contextual Argument index'),
      '#description' => $this->t('Contextual arg field index, starting from 0'),
      '#type' => 'textfield',
      '#default_value' => $this->options['hid_argument'],
    ];
    $form['pid_argument'] = [
      '#title' => $this->t('Person ID Contextual Argument index'),
      '#description' => $this->t('Contextual arg field index, starting from 0'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid_argument'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
    if (strlen($this->options['pid_argument']) && strlen($this->options['hid_argument'])) {
      $pid_argument = $this->options['pid_argument'];
      $hid_argument = $this->options['hid_argument'];
      if (isset($this->view->args[$pid_argument]) && isset($this->view->args[$hid_argument])) {
        $pids_string = $this->view->args[$pid_argument];
        $hid = $this->view->args[$hid_argument];
        // Support multiple PIDs like 5+9+114.
        $pids = explode('+', $pids_string);
        if ($pids && $hid) {
          $dids = DdHearing::getHearingBillDiscussionIdsForSpeaker($hid, $pids);
          if ($dids) {
            $this->query->addWhere($this->options['group'], 'BillDiscussion_Hearing.did', $dids, 'IN');
          }
          else {
            // @todo This shouldn't be reached based on facets filtering correctly.
          }
        }
      }
    }
  }

}
