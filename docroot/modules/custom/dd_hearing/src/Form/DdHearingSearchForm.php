<?php

namespace Drupal\dd_hearing\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\dd_committee\Entity\DdCommittee;

/**
 * Form controller for DD Hearing search forms.
 *
 * @ingroup dd_hearing
 */
class DdHearingSearchForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_hearing_search_box_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['input'] = array(
      '#type' => 'textfield',
      '#title' => '',
      '#placeholder' => t('SEARCH HEARING STATEMENTS'),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {;
    $cn_ids = [];
    $date_ts = '';

    $parameters = \Drupal::routeMatch()->getParameters();

    if (!is_null($parameters->get('dd_hearing'))) {
      $hid = $parameters->get('dd_hearing')->id();
      $hearing = DdHearing::load($hid);
      $committee_ids = DdHearing::getCommitteeIdsForHearing($hid);
      $date_ts = \Drupal::service('date.formatter')->format($hearing->getDate(), 'custom', 'Y-m-d');
      if ($committee_ids) {
        $committees = DdCommittee::loadMultiple($committee_ids);
        foreach ($committees as $committee) {
          $cn_ids[] = $committee->getCommitteeNameId();
        }
      }
    }
    else {
      // Committees page uses cn_id as contextual argument.
      $cn_ids = [$parameters->get('cn_id')];
    }

    // Use only 1st committee name id since search doesn't support multiples yet.
    $cn_id = array_shift($cn_ids);

    $fulltext = Html::escape(SafeMarkup::checkPlain($form_state->getValue('input')));

    $options = array(
      'query' => array(
        'fulltext' => $fulltext,
        'date_ts' => $date_ts,
        'date_ts_1' => $date_ts,
        'sort_by' => 'search_api_relevance',
        'cn_id' => $cn_id,
        'sort_order' => 'DESC',
      ),
    );

    $url = Url::fromUserInput('/search', $options);
    $form_state->setRedirectUrl($url);
  }

}
