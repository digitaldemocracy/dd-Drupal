<?php

namespace Drupal\dd_search\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\views\ViewExecutable;

class DdSearchForm implements FormInterface {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ViewExecutable $view = NULL, $output = []) {
    $form['dd_search_term'] = array(
      '#type' => 'textfield',
      '#title' => 'Search',
      '#placeholder' => 'Search for Bills, Hearings, Speakers, Committees, Organizations',
      '#size' => 120,
    );

    if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_BASE) {
      $url = \Drupal\Core\Url::fromRoute('dd_base.dd_state_select_controller_ShowStateSelectBlock');
      $form['stateselectcontainer'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['state-select-container'],
        ],
        'stateselectlink' => [
          '#type' => 'link',
          '#url' => $url,
          '#attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => \Drupal\Component\Serialization\Json::encode([
              'width' => 400,
            ]),
          ],
          '#title' => t('Open state'),
        ],
      ];
    }
    else {
      $form['dd_search_term']['#autocomplete_route_name'] = 'dd_search.autocomplete';

      $form['submit'] = array(
        '#type' => 'submit',
        '#attributes' => array('class' => array('dd-search-submit')),
        '#value' => t('Search'),
      );
    }

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
    // Send user to search page w/term.
    $url = Url::fromUserInput('/search', array('query' => array('fulltext' => $form_state->getValue('dd_search_term'))));
    $form_state->setRedirectUrl($url);
  }

}
