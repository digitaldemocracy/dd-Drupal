<?php

namespace Drupal\dd_bill\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;
use Drupal\dd_bill\Entity\DdBill;

/**
 * Form controller for DD Bill search forms.
 *
 * @ingroup dd_bill
 */
class DdBillStatementSearchForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_bill_statement_search_box_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['input'] = array(
      '#type' => 'textfield',
      '#title' => '',
      '#placeholder' => t('SEARCH STATEMENTS ON THIS BILL'),
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
    $parameters = \Drupal::routeMatch()->getParameters();

    if (!is_null($parameters->get('dd_bill_bid'))) {
      $bid = $parameters->get('dd_bill_bid');
      // Load bill to get type and number
      $bill_id = DdBill::getBillbyBid($bid);
      $bill = DdBill::load($bill_id->dr_id);
      if (!is_null($bill)) {
        $type = $bill->getType();
        $number = $bill->getNumber();
      }
    }
    else {
      $type = '';
      $number = '';
    }

    $fulltext = Html::escape(SafeMarkup::checkPlain($form_state->getValue('input')));

    $options = array(
      'query' => array(
        'fulltext' => $fulltext,
        'sort_by' => 'search_api_relevance',
        'sort_order' => 'DESC',
        'type' => $type,
        'number' => $number,
      ),
    );

    $url = Url::fromUserInput('/search', $options);
    $form_state->setRedirectUrl($url);
  }

}
