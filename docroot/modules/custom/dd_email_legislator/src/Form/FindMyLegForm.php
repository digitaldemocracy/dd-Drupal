<?php
/**
 * @file
 * Contains \Drupal\dd_email_legislator\Form\FindMyLegForm.
 */

namespace Drupal\dd_email_legislator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\dd_email_legislator\Utility\CommonHelper;

/**
 * Contribute form for find my legislators form.
 */
class FindMyLegForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_email_legislator_find_my_leg_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];

    $form['embed_url'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['embed_url'],
    );

    $form['address'] = array(
      '#type' => 'fieldset',
      '#title' => 'Your Address',
    );

    $form['address']['state'] = array(
      '#type' => 'select',
      '#title' => 'State',
      '#default_value' => 'CA',
      '#options' => array('CA' => 'CA', 'NY' => 'NY'),
    );

    $form['address']['zipcode'] = array(
      '#type' => 'textfield',
      '#title' => 'Zip Code',
    );

    $form['address']['city'] = array(
      '#type' => 'textfield',
      '#title' => 'City',
    );

    $form['address']['street'] = array(
      '#type' => 'textfield',
      '#title' => 'Street',
    );

    $form['bcc'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['bcc'],
    );

    $form['find'] = array(
      '#type' => 'submit',
      '#value' => 'Find My Legislator',
      '#ajax' => array(
        'callback' =>
          '\Drupal\dd_email_legislator\Form\FindMyLegForm::submitFormCallback',
        'prevent' =>
          'click submit',
      ),
    );

    $form['cancel'] = array(
      '#markup' => '<a href="' . $build_args['back_link'] . '">Go Back</a>',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate the form values.
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * a function to be called when the form is submitted via ajax
   *
   * @param array $form 
   *   array of form elements
   * @param FormStateInterface $form_state 
   *   object representing form state
   *
   * @return object 
   *   AjaxResponse object passed back to browser 
   */
  public static function submitFormCallback(array &$form, FormStateInterface $form_state) {
    $form_data = $form_state->getValues();
    if (!($form_data['state'] && $form_data['zipcode'] && $form_data['city'] && $form_data['street'])) {
      return CommonHelper::ajaxErrorResponse(
             "Please fill in all required fields." 
           );
    }

    $address = array(
                 'state' => $form_data['state'],
                 'zipcode' => $form_data['zipcode'],
                 'city' => $form_data['city'],
                 'street' => str_replace('.','',$form_data['street']),
               );

    $result = CommonHelper::findLegislators($address);
    if (!$result) {
      return CommonHelper::ajaxErrorResponse(
             "Error : The server encountered some problems " 
             . "while searching for your legislators."
           );
    }
    if (!isset($result['data'])) {
      return CommonHelper::ajaxErrorResponse(
             "Error : ".$result['message']
           );
    }

    $bcc = $form_data['bcc'];
    $msg_body = 'Watch this clip on Digital Democracy! ' . $form_data['embed_url'];
    $markup = \Drupal::moduleHandler()->invokeAll('legislators_contact_form', [
          $address,
          $result['data']['legislators'],
          $msg_body,
          $bcc
        ]);
    $response = new AjaxResponse();
    $response->addCommand(
      new ReplaceCommand('#my-legislators',
                         '<div id="my-legislators">'.$markup[0].'</div>'));
    $response->addCommand(
      new InvokeCommand(NULL, "setLegList", array('#leg-contact-list')));
    return $response;
  }
}

