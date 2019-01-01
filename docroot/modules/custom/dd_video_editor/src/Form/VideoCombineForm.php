<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Form\VideoCombineForm.
 */

namespace Drupal\dd_video_editor\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use \Drupal\dd_video_editor\Utility\CommonHelper;
use Drupal\Core\Url;
    
/**
 * Contribute form for combining multiple clips.
 */
class VideoCombineForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_editor_video_combine_form';
  }
    
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['hidden'] = array(
      '#type' => 'fieldset',
      '#prefix' => '<div class="hide">',
      '#suffix' => '</div>',
    );

    $form['hidden']['videos'] = array(
      '#type' => 'textfield',
      '#default_value' => '',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#ajax' => array(
        'callback' => '\Drupal\dd_video_editor\Form\VideoCombineForm::submitFormCallback',
      ),
      '#value' => 'COMBINE CLIPS',
    );

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
    $user = \Drupal::currentUser();
    $upgrade_link =
      Url::fromRoute('dd_payment_system.subscribe',
                     ['user'=>$user->id()])->toString();

    $videos = array();
    $temp = explode(",", $form_data['videos']);

    // do not allow clip save if it exceeds the quata
    $size_limit = CommonHelper::getQuota();
    if ($size_limit >= 0 && $size_limit < CommonHelper::getUsage()) {
      return CommonHelper::ajaxErrorResponse(
                    'The clip could not be created because'
                    .' you have already reached the maximum storage size of '
                    . dd_clip_format_size_units($size_limit)
                    .'. Please delete some clips in your clip bank or '
                    .'<a style="text-decoration: underline;" href="'
                    .$upgrade_link
                    .'">upgrade your membership</a>.');
    }

    if (count($temp) <= 1) {
      return CommonHelper::ajaxErrorResponse(
        'Choose more than one clip to combine.');
    }

    $clip_size = 0;
    foreach($temp as $video) {
      $clip_size += CommonHelper::getClipSize(trim($video));
      array_push($videos, trim($video));
    }
    if ($size_limit >= 0 && $size_limit <
        (CommonHelper::getUsage() + $clip_size)) {
      return CommonHelper::ajaxErrorResponse(
        'The size of the clip would be '.dd_clip_format_size_units($clip_size)
        .' and you would exceed the maximum storage size of '
        . dd_clip_format_size_units($size_limit)
        .'. Please delete some clips in your clip bank or '
        .'<a style="text-decoration: underline;" href="'
        .$upgrade_link
        .'">upgrade your membership</a>.');
    }

    $result = CommonHelper::concatVideos($user->id(), $videos);
    if (!$result || !isset($result['size'])) {
      return CommonHelper::ajaxErrorResponse(
             "An error occurred while creating the clip. Error code: "
             . $result
           );
    }
    $response = new AjaxResponse();
    $response->addCommand(
      new InvokeCommand(NULL, "playVideo", array($result)));
    sleep(2);
    return $response; 
  }
}
