<?php
/**
 * @file
 * Contains \Drupal\dd_email_editor\Form\FindNewspapersForm.
 */

namespace Drupal\dd_email_editor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\dd_email_editor\Utility\CommonHelper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contribute form for find my editors form.
 */
class FindNewspapersForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_email_editor_find_newspapers_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];
    $is_newspapers_override = (isset($build_args['newspapers_override']) && strlen($build_args['newspapers_override']) != 0);

    // Build form with or without address form, depending on values passed in.
    if (isset($build_args['embed_url'])) {
      $form['embed_url'] = array(
        '#type' => 'hidden',
        '#value' => $build_args['embed_url'],
      );
    }
    $show_address_form = TRUE;

    if (isset($build_args['street'])) {
      $show_address_form = FALSE;
    }

    if ($show_address_form) {
      $form['address'] = array(
        '#type' => 'fieldset',
        '#title' => 'Your Address',
      );
    }

    $form['address']['state'] = array(
      '#type' => ($show_address_form ? 'select' : 'hidden'),
      '#title' => 'State',
      '#default_value' => ($build_args['state'] == '') ? 'CA' : $build_args['state'],
      '#options' => array('CA' => 'CA', 'NY' => 'NY'),
    );

    $form['address']['zipcode'] = array(
      '#type' => ($show_address_form ? 'textfield' : 'hidden'),
      '#title' => 'Zip Code',
      '#default_value' => $build_args['zip'],
    );

    $form['address']['city'] = array(
      '#type' => ($show_address_form ? 'textfield' : 'hidden'),
      '#title' => 'City',
      '#default_value' => $build_args['city'],
    );

    $form['address']['street'] = array(
      '#type' => ($show_address_form ? 'textfield' : 'hidden'),
      '#title' => 'Street',
      '#default_value' => $build_args['street'],
    );

    $options = [5 => 5, 10 => 10, 25 => 25, 50 => 50, 100 => 100, 200 => 200];

    // Only show range when newspapers not overridden.
    if (!$is_newspapers_override) {
      $form['range'] = array(
        '#type' => 'select',
        '#title' => 'Range In Miles',
        '#options' => $options,
        '#default_value' => 25,
      );
    }

    if ($show_address_form) {
      $form['in_state'] = array(
        '#type' => 'checkbox',
        '#title' => 'Find all in the state',
      );
    }

    // Only show update button when newspapers not overridden.
    if (!$is_newspapers_override) {
      $form['update'] = array(
        '#type' => 'button',
        '#value' => 'Find Newspapers',
        '#ajax' => array(
          'callback' =>
            '\Drupal\dd_email_editor\Form\FindNewspapersForm::submitFormCallback',
        ),
      );
    }
    else {
      // Instead of ajax, just add the markup to form.
      $form['newspapers'] = [
        '#type' => 'markup',
        '#markup' => $this->submitFormCallback($form, $form_state, \Drupal::request(), TRUE),
      ];
    }


    if ($show_address_form) {
      $form['cancel'] = array(
        '#markup' => '<a href="' . $build_args['back_link'] . '">Go Back</a>',
      );
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate the form values.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
  }

  /**
   * A function to be called when the form is submitted via ajax.
   *
   * @param array $form 
   *   array of form elements
   * @param FormStateInterface $form_state 
   *   object representing form state
   * @param Request $request
   *   HTTP Request
   *
   * @return object 
   *   AjaxResponse object passed back to browser 
   */
  public static function submitFormCallback(array &$form, FormStateInterface $form_state, Request $request, $return_markup = FALSE) {
    $build_args = $form_state->getBuildInfo()['args'][0];

    $form_data = $form_state->getValues();
    if (!($form_data['state'] && $form_data['zipcode'] && $form_data['city'] && $form_data['street']) && ($form_data['in_state'] && !$form_data['state'])) {
      return CommonHelper::ajaxErrorResponse(
             "Please fill in all required fields." 
           );
    }

    if ($form_data['in_state']) {
      $result = CommonHelper::findNewspapersInState($form_data['state']);
    }
    elseif (isset($build_args['newspapers_override']) && strlen($build_args['newspapers_override']) != 0) {
      // Filter newspaper list to only the overrides given.
      $result = CommonHelper::findNewspapersInState($build_args['state']);
      $newspapers_override = explode(',', $build_args['newspapers_override']);
      $newspapers_override = array_map('trim', $newspapers_override);
      foreach ($result['data'] as $key => $newspaper) {
        if (!in_array($newspaper['name'], $newspapers_override)) {
          unset($result['data'][$key]);
        }
      }
      if (!$result || !isset($result['data']) || count($result['data']) == 0) {
        return "<h1>Error</h1><p>No newspapers found!</p>";
      }
    }
    else {
      $address = array(
                   'state' => $form_data['state'],
                   'zipcode' => $form_data['zipcode'],
                   'city' => $form_data['city'],
                   'street' => str_replace('.','',$form_data['street']),
                 );

      $result = CommonHelper::findNewspapers($address, intval($form_data['range']));
    }
    if (!$result) {
      return CommonHelper::ajaxErrorResponse(
             "Error : The server encountered some problems " 
             . "while searching for your editors."
           );
    }
    if (!isset($result['data'])) {
      return CommonHelper::ajaxErrorResponse(
             "Error : ".$result['message']
           );
    }

    $msg_body = '';
    if (isset($build_args['body'])) {
      $msg_body = $build_args['body'];
    }

    if (!empty($form_state->get('embed_url'))) {
      $msg_body .= "\nWatch this clip on Digital Democracy!\n" . $form_data['embed_url'];
    }
    else if (isset($build_args['embed_url'])) {
      $msg_body .= "\nWatch this clip on Digital Democracy!\n" . $build_args['embed_url'];
    }

    $msg_subject = '';
    if (isset($build_args['subject'])) {
      $msg_subject = $build_args['subject'];
    }

    $config = \Drupal::configFactory()->getEditable('dd_admin.DdAdminEmailSettings');
    $bcc = $config->get('bcc_address');

    $links = CommonHelper::convertToEmailLinks($msg_body, $msg_subject,
                                               $result['data'], $bcc);
    $render_data = ['#theme' => 'dd-email-editor-contact-links', '#links' => $links['newspapers']];
    $content = \Drupal::service('renderer')->renderRoot($render_data);
    $markup = $content->__toString();
    if ($return_markup) {
      return $markup;
    }

    $response = new AjaxResponse();
    $response->addCommand(
      new ReplaceCommand('#my-newspapers',
                         '<div id="my-newspapers">'.$markup.'</div>'));
    return $response;
  }
}

