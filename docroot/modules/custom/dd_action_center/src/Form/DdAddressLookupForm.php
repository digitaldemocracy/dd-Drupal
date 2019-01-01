<?php
/**
 * @file
 * Address Lookup Form.
 */

namespace Drupal\dd_action_center\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\Url;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_base\DdBase;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Drupal\user\Entity\User;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DdAddressLookupForm extends FormBase implements FormInterface {
  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * Constructs a DdAddressLookupForm.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   *   Private Temp Storage Factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   *   Session Manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current User
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;

    $this->store = $this->tempStoreFactory->get('dd_campaign_visitor');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.private_tempstore'),
      $container->get('session_manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_address_lookup_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user = DdActionCenterCampaignHelper::getVisitor();

    if ($user) {
      if (\Drupal::currentUser()->id()) {
        $form['settings_redirect'] = [
          '#type' => 'submit',
          '#value' => t('Change Address'),
        ];
      }
      else {
        $form['change_location'] = [
          '#type' => 'submit',
          '#value' => t('Change Address'),
        ];
      }
      $form['#attached']['library'][] = 'dd/show_targeted_actions';
    }
    else {
      // @todo make this configurable.
      $form['calltoaction'] = array(
        '#type' => 'item',
        '#title' => t('Enter your address'),
      );

      $form['first_name'] = array(
        '#type' => 'textfield',
        '#title' => 'First Name',
        '#default_value' => '',
        '#required' => TRUE,
        '#size' => 20,
      );

      $form['last_name'] = array(
        '#type' => 'textfield',
        '#title' => 'Last Name',
        '#default_value' => '',
        '#required' => TRUE,
        '#size' => 20,
      );

      $form['addressline1'] = array(
        '#type' => 'textfield',
        '#title' => 'Address',
        '#default_value' => '',
        '#size' => 120,
        '#required' => TRUE,
      );

      $form['city'] = array(
        '#type' => 'textfield',
        '#title' => 'City',
        '#default_value' => '',
        '#size' => 12,
        '#required' => TRUE,
      );

      $form['state'] = array(
        '#type' => 'textfield',
        '#title' => 'State',
        '#default_value' => DdBase::getCurrentState(),
        '#size' => 2,
        '#required' => TRUE,
      );

      $form['zip'] = array(
        '#type' => 'textfield',
        '#title' => 'Zip',
        '#default_value' => '',
        '#size' => 10,
        '#required' => TRUE,
      );

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Take Action'),
      );
    }
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Don't validate if going to change form again.
    if ($form_state->getValue('settings_redirect') || $form_state->getValue('change_location')) {
      return;
    }

    if (strlen($form_state->getValue('state')) != 2) {
      $form_state->setErrorByName('state', t('Please enter the two letter abbreviation code of your state.'));
      return;
    }

    $form_state->setValue('first_name', ucwords($form_state->getValue('first_name')));
    $form_state->setValue('last_name', ucwords($form_state->getValue('last_name')));
    $form_state->setValue('addressline1', ucwords($form_state->getValue('addressline1')));
    $form_state->setValue('city', ucwords($form_state->getValue('city')));
    $form_state->setValue('state', strtoupper($form_state->getValue('state')));

    $address = [
      'state' => $form_state->getValue('state'),
      'zipcode' => $form_state->getValue('zip'),
      'city' => $form_state->getValue('city'),
      'street' => $form_state->getValue('addressline1'),
    ];
    $result = \Drupal\dd_find_legislators\Utility\CommonHelper::findLegislators($address);
    if ($result['message'] != 'success' || !isset($result['data'])) {
      $form_state->setErrorByName('addressline1', t('Could not locate this address'));
    }
    else {
      // Check if logged in user address should be updated.
      if ($uid = \Drupal::currentUser()->id()) {
        $user = User::load($uid);

        $field_address = $user->get('field_address')[0];

        // User's address is empty, update it.
        if (empty($field_address)) {
          $address['country_code'] = 'US';
          $address['address_line1'] = $form_state->getValue('addressline1');
          $address['locality'] = $form_state->getValue('city');
          $address['administrative_area'] = $form_state->getValue('state');
          $address['postal_code'] = $form_state->getValue('zip');
          $user->set('field_address', $address);

          if (empty($user->get('field_first_name')->value)) {
            $user->set('field_first_name', $form_state->getValue('first_name'));
            $user->set('field_last_name', $form_state->getValue('last_name'));
          }
          $user->save();
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $args = $form_state->getBuildInfo()['args'][0];

    if ($form_state->getValue('settings_redirect')) {
      $uid = \Drupal::currentUser()->id();
      $current_path = \Drupal::service('path.current')->getPath();
      $result = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);

      $url = Url::fromRoute('entity.user.edit_form', ['user' => $uid], ['query' => ['destination' => $result]]);
      $form_state->setRedirectUrl($url);
    }
    elseif ($form_state->getValue('change_location')) {
      $this->store->delete('user');
    }
    else {
      $address['country_code'] = 'US';
      $address['address_line1'] = $form_state->getValue('addressline1');
      $address['locality'] = $form_state->getValue('city');
      $address['administrative_area'] = $form_state->getValue('state');
      $address['postal_code'] = $form_state->getValue('zip');
      $data['field_address'] = $address;
      $data['field_first_name'] = $form_state->getValue('first_name');
      $data['field_last_name'] = $form_state->getValue('last_name');

      // Start session for anonymous users if necessary.
      if ($this->currentUser->isAnonymous() && !isset($_SESSION['session_started'])) {
        $_SESSION['session_started'] = TRUE;
        $this->sessionManager->start();
      }
      $user = User::create($data);
      $this->store->set('user', $user);

      // Log Campaign Action.
      \Drupal::service('dd_metrics.logger')
        ->logCampaignMetric($args['campaign_id'], DdCampaignMetricTypes::DD_METRICS_CAMPAIGN_ADDRESS_FORM_SUBMITTED);
    }
  }
}
