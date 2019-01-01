<?php
/**
 * @file
 * Contains \Drupal\dd_fax_service\Form\PreviewFaxForm.
 */

namespace Drupal\dd_fax_service\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use \Drupal\dd_fax_service\Utility\CommonHelper;
use \Drupal\dd_fax_service\DdFaxService;
use Symfony\Component\DependencyInjection\ContainerInterface;
   
/**
 * Contribute form for preview fax form.
 */
class FaxPreviewForm extends FormBase {
  /**
   * @var \Drupal\dd_fax_service\DdFaxService*/
  protected $faxService;

  /**
   * {@inheritdoc}
   */
  public function __construct(DdFaxService $fax_service) {
    $this->faxService = $fax_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dd_fax_service.dd_fax_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_fax_service_preview_fax_form';
  }
    
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];

    $form['fax_num'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['fax_num'],
    );

    $form['to'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['to'],
    );

    $form['subject'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['subject'],
    );

    $form['body'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['body'],
    );

    $form['first'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['first'],
    );

    $form['last'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['last'],
    );

    $form['contact'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['contact'],
    );

    $form['state'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['state'],
    );

    $form['zip'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['zip'],
    );

    $form['city'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['city'],
    );

    $form['street'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['sender']['street'],
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Send',
    );

    /*$form['cancel'] = array(
      //'#markup' => '<a href="' . $build_args['back_link'] . '">Cancel</a>',
    );*/

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
    if (dd_fax_service_check_limit_reached()) {
      $build_args = $form_state->getBuildInfo()['args'][0];
      $form_data = $form_state->getValues();
      $sender = array(
        'first' => $form_data['first'],
        'last' => $form_data['last'],
        'contact' => $form_data['contact'],
        'state' => $form_data['state'],
        'zip' => $form_data['zip'],
        'city' => $form_data['city'],
        'street' => $form_data['street'],
      );
      $fax_data = CommonHelper::buildFaxData($form_data['fax_num'],
        $form_data['to'],
        $form_data['subject'],
        $form_data['body'], $sender);

      $result = $this->faxService->sendHtmlStringData('+1'.$form_data['fax_num'],
        $fax_data);
      if ($result && $result->succeeded()) {
        drupal_set_message($result->getMessage());
        dd_fax_service_increment_faxes_sent();

        if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL && isset($build_args['campaign_id'])) {
          // Log Action Metric Conversion.
          \Drupal::service('dd_metrics.logger')
            ->logActionConversion($build_args['campaign_id'], $build_args['campaign_action_id'], $build_args['campaign_action_paragraphs_id'], $build_args['action_id'], $build_args['target_pid']);
        }
      }
      else {
        drupal_set_message($result ? $result->getMessage() :
          'Failed to send the fax.', 'error');
      }
    }
    else {
      drupal_set_message("You have reached the limit of the number of faxes that you can send.");
    }
  }
}
