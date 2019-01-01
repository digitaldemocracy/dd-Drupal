<?php

namespace Drupal\dd_person\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;

/**
 * Form controller for DD Hearing search forms.
 *
 * @ingroup dd_hearing
 */
class DdSpeakerSearchForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_speaker_search_box_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['input'] = array(
      '#type' => 'textfield',
      '#title' => 'Search everything this speaker has said',
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $parameters = \Drupal::routeMatch()->getParameters();
    $speaker_pid = '';

    if (!is_null($parameters->get('dd_person'))) {
      $speaker_pid = $parameters->get('dd_person')->getFullNameFirstLast() . ' (' . $parameters->get('dd_person')->id() . ')';
    }

    $fulltext = Html::escape(SafeMarkup::checkPlain($form_state->getValue('input')));

    $options = array(
      'query' => array(
        'fulltext' => $fulltext,
        'sort_by' => 'search_api_relevance',
        'speaker_pid' => $speaker_pid,
        'sort_order' => 'DESC',
      ),
    );

    $url = Url::fromUserInput('/search', $options);
    $form_state->setRedirectUrl($url);
  }

}
