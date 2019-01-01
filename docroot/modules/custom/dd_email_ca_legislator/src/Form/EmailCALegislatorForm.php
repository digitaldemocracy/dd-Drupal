<?php

namespace Drupal\dd_email_ca_legislator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_metrics\Entity\DdCampaignVisitor;
use Drupal\dd_metrics\Utility\DdCampaignVisitorHelper;

class EmailCALegislatorForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_email_ca_legislator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, 
                            $house = NULL,
                            $district = NULL, 
                            $pid = NULL) {

    $arg_str = \Drupal::request()->getQueryString();
    parse_str($arg_str, $args);
    $form['#attached']['library'][] = 'dd/dd-email-ca-legislator';

    $form['required'] = array(
      '#type' => 'item',
      '#title' => 'Fields marked with an (*) are required.',
    );

    $form['district'] = array(
      '#type' => 'hidden',
      '#default_value' => $district,
    );

    $form['campaign_id'] = array(
      '#type' => 'hidden',
      '#default_value' => isset($args['campaign_id']) ? $args['campaign_id'] : '',
    );

    $form['name'] = array(
      '#type' => 'fieldset',
      '#title' => '<b>Your Name:</b>',
      '#required' => true,
      '#attributes' => array('class' => array('container-inline')),
    );

    $form['name']['txtFirstName'] = array(
      '#type' => 'textfield',
      '#title' => 'First',
      '#default_value' => (array_key_exists('first', $args) ? $args['first'] : ''),
    );

    $form['name']['txtLastName'] = array(
      '#type' => 'textfield',
      '#title' => 'Last',
      '#default_value' => (array_key_exists('last', $args) ? $args['last'] : ''),
    );

    $form['txtAddress'] = array(
      '#type' => 'textfield',
      '#title' => 'Address:',
      '#required' => true,
      '#default_value' => urldecode($args['street']),
    );

    $form['txtCity'] = array(
      '#type' => 'textfield',
      '#title' => 'City:',
      '#required' => true,
      '#default_value' => urldecode($args['city']),
    );
    
    $form['txtZip'] = array(
      '#type' => 'textfield',
      '#title' => 'Zip Code:',
      '#required' => true,
      '#default_value' => $args['zip'],
    );

    $form['phone'] = array(
      '#type' => 'fieldset',
      '#title' => '<b>Phone Number:</b>',
      '#attributes' => array('class' => array('container-inline')),
    );

    $form['phone']['txtAreaCode'] = array(
      '#type' => 'tel',
      '#title' => '',
      '#attributes'=>array('onKeyPress'=>"return(this.value.length<3);"),
    );

    $form['phone']['txtPhone1'] = array(
      '#type' => 'tel',
      '#title' => '',
      '#attributes'=>array('onKeyPress'=>"return(this.value.length<3);"),
    );

    $form['phone']['txtPhone2'] = array(
      '#type' => 'tel',
      '#title' => '',
      '#attributes'=>array('onKeyPress'=>"return(this.value.length<4);"),
    );

    $form['phone']['txtExt'] = array(
      '#type' => 'tel',
      '#title' => 'Ext',
    );

    $form['phone']['phoneTypeListbox'] = array(
      '#type' => 'select',
      '#title' => 'Type',
      '#default_value' => 'home',
      '#options' => array('home' => 'home', 'work' => 'work', 'cell' => 'cell'),
    );

    $form['txtEmail'] = array(
      '#type' => 'email',
      '#title' => 'E-mail:',
      '#required' => true,
      '#default_value' => (array_key_exists('email', $args) ? urldecode($args['email']) : ''),
    );
    
    $form['jv_text'] = array(
      '#type' => 'textarea',
      '#title' => 'Message:',
      '#default_value' => urldecode($args['message']),
    );

    $form['bcc'] = array(
      '#type' => 'hidden',
      '#value' => (array_key_exists('bcc', $args) ? urldecode($args['bcc']) : ''),
    );

    $form['leg_name'] = array(
      '#type' => 'hidden',
      '#value' => (array_key_exists('name', $args) ? urldecode($args['name']) : ''),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    return $form;
  }

  /*
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate form values.

    if (!($form_state->getValue('txtFirstName'))) {
      $form_state->setErrorByName('txtFirstName', $this->t('First name field must be filled out.'));
    }
    if (!($form_state->getValue('txtLastName'))) {
      $form_state->setErrorByName('txtLastName', $this->t('Last name field must be filled out.'));
    }
    $count = 0;
    if ($form_state->getValue('txtAreaCode')) {
      $count++;
    }
    if ($form_state->getValue('txtPhone1')) {
      $count++;
    }
    if ($form_state->getValue('txtPhone2')) {
      $count++;
    }
    if ($count !== 3 && $count !== 0) {
      $form_state->setErrorByName('phone', $this->t('Phone fields must either be all filled or none at all.' . $count));
    } 
  }

  /*
   * {@inheritdoc}
   * Using curl to send form field data to the CA state government site.
   */
  public function submitForm(array &$form, FormStateInterface $form_state, $name = NULL) {
    // Submit form
    // district, inframe=Y, headerimg=Y, txtFirstName, txtLastName, 
    // txtAddress, txtCity, listboxStates, txtZip, txtEmail,
    // text_num, jv_text, submitButton=Submit

    $values = $form_state->getValues();
    $post_items = array();
    $post_items[] = 'inframe=Y';
    $post_items[] = 'headerimg=Y';
    foreach ($values as $key => $value) {
      if ($key !== 'submit' && $key !== 'form_build_id' &&
          $key !== 'form_token' && $key !== 'form_id' &&
          $key !== 'op' && $key !== 'bcc' && $key !== 'leg_name' &&
          $key !== 'campaign_id'
      ) {
          $post_items[] = $key . '=' . urlencode($value);
      }
    }

    $text_num = 2000 - strlen($values['jv_text']);
    $post_items[] = 'text_num=' . $text_num;
    $post_items[] = 'submitButton=Submit';

    
    $post_string = implode('&', $post_items);

    $curl_connection = curl_init("https://lcmspubcontact.lc.ca.gov/PublicLCMS/ContactPopupSubmit.php");
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_USERAGENT,
      "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

    $result = curl_exec($curl_connection);
    $err_no = curl_errno($curl_connection);
    $error_msg = curl_error($curl_connection);
    curl_close($curl_connection);
     
    if ($result && $err_no === 0) {
      drupal_set_message('Message was successfully sent.');
      $bcc = $values['bcc'] ? $values['bcc'] :
             $this->config('dd_admin.DdAdminEmailSettings')->get('bcc_address');
      if ($bcc) {
        $this->sendCopy($bcc, $this->constructBccMessage($values));
      }
    } else {
      drupal_set_message('Message failed to send. ' . $result);
      drupal_set_message($error_msg);
      \Drupal::logger('dd_email_ca_legislator')->error($err_no . ": " . $error_msg);
    }

    \Drupal::logger('dd_email_ca_legislator')->notice($result); 

    // Update campaign visitor's email for campaign if empty.
    if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL && !empty($values['campaign_id'])) {
      $visitor_user = DdActionCenterCampaignHelper::getVisitor();
      $visitor_id = DdCampaignVisitorHelper::checkIfVisitorExists($visitor_user, $values['campaign_id']);
      if ($visitor_id) {
        $entity = DdCampaignVisitor::load($visitor_id);
        if (empty($entity->getEmail())) {
          $entity->setEmail($values['txtEmail']);
          $entity->save();
        }
      }
    }
  }

  private function sendCopy($address, $msg) {
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'dd_email_ca_legislator';
    $key = 'email_ca_legislator';
    $params['message'] = $msg;
    $params['subject'] = 'A message sent to a legislator by a member';
    $result = $mailManager->mail($module, $key, $address, "und",
                                 $params, NULL, true);
  }

  private function constructBccMessage($msg_items) {
    $msg = "A message was sent to {$msg_items['leg_name']}\r\n";
    $msg .= "Legislator : {$msg_items['leg_name']}\r\n";
    $msg .= "District : {$msg_items['district']}\r\n";
    $msg .= "Sender Name : {$msg_items['txtFirstName']} {$msg_items['txtLastName']}\r\n";
    $msg .= "Sender Email : {$msg_items['txtEmail']}\r\n";
    $msg .= "Sender Address : {$msg_items['txtAddress']}\r\n";
    $msg .= "Sender City : {$msg_items['txtCity']}\r\n";
    $msg .= "Sender Zip : {$msg_items['txtZip']}\r\n";
    $msg .= "Message Body : {$msg_items['jv_text']}\r\n";
    return $msg;
  }
}
