<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Form\VideoBuilderForm.
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
 * Contribute form for saving a newly created clip.
 */
class VideoBuilderForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_editor_video_builder_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $form['#attributes'] = array('class' => 'clearfix');
    $form['video'] = array(
      '#markup' => '<video id="player1" class="video-js vjs-default-skin"
                    controls preload="auto" width="434" height="243">
                    </video>',
      '#title' => 'video',
    );
    
    $form['hide'] = array(
      '#type' => 'fieldset',
      '#prefix' => '<div class="hide">',
      '#suffix' => '</div>',
    );

    $form['hide']['videoid'] = array(
      '#type' => 'hidden',
      '#default_value' => '',
    );

    $form['hide']['videos_rendered'] = array(
      '#type' => 'hidden',
      '#default_value' => '',
    );

    $form['hide']['duration'] = array(
      '#type' => 'hidden',
      '#default_value' => '',
    );

    $form['hide']['size'] = array(
      '#type' => 'hidden',
      '#default_value' => '',
    );

    $form['body'] = array(
      '#type' => 'fieldset',
    );

    $form['body']['clip_name'] = array(
      '#type' => 'textfield',
      '#title' => 'Title of clip:',
      '#size' => 20,
    );

    $form['body']['note'] = array(
      '#type' => 'textarea',
      '#title' => 'Private Note',
      '#size' => 200,
    );

    if (CommonHelper::isPermitted('attach transcripts to clips')) {
      $form['body']['insert_transcript'] = array(
        '#type' => 'submit',
        '#ajax' => array(
          'callback' => '\Drupal\dd_video_editor\Form\VideoBuilderForm::buildTranscriptCallback',
        ),
        '#value' => 'Append transcript',
      );

      $form['body']['commentary'] = array(
        '#type' => 'text_format',
        '#default_value' => '', 
        '#title' => 'Commentary'
      );

      $form['body']['display_transcript'] = array(
        '#type' => 'checkbox',
        '#title' => 'Display Transcript',
        '#default_value' => 0,
        '#return_value' => 1,
      );
    }

    $form['body']['video_tags'] = array(
      '#type' => 'textfield',
      '#title' => 'Clip Tag',
      '#autocomplete_route_name' => 'dd_video_editor.tag_autocomplete',
      //'#autocomplete_route_parameters' => array('vocab_name' => 'video_tags'),
      '#description' =>
        'Input values to tag clip with. Separate tags with a comma.' .
        ' For example: "Tag 1, Tag 2, Tag 3".',
      '#suffix' => CommonHelper::buildUserTags(),
    );
 
    $form['actions'] = array(
      '#type' => 'fieldset',
      '#prefix' => '<div class="vc-btns-save-cancel">',
      '#suffix' => '</div>',
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#ajax' => array(
        'callback' =>
          '\Drupal\dd_video_editor\Form\VideoBuilderForm::submitFormCallback',
      ),
      '#value' => 'Create',
      '#prefix' => '<div class="vc-save">',
      '#suffix' => '</div>',
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
    // Display result.
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
  public static function submitFormCallback(array &$form,
                                            FormStateInterface $form_state) {
    $form_data = $form_state->getValues();
    $user = \Drupal::currentUser();
    $upgrade_link =
      Url::fromRoute('dd_payment_system.subscribe',
                     ['user'=>$user->id()])->toString();

    $msg = array();
    foreach ($form_data as $key => $value) {
      $msg[]=$key . ': ' . $value;
    }

    $size_limit = CommonHelper::getQuota();
    // do not allow clip save if the title is not filled in 
    if (!isset($form_data['clip_name']) || $form_data['clip_name'] === "") {
      return CommonHelper::ajaxErrorResponse(
               'Please fill in the "CLIP NAME" field.');
    }
    
    if ($size_limit >= 0 && $size_limit < CommonHelper::getUsage()) {
      return CommonHelper::ajaxErrorResponse(
                      'The clip could not be saved because'
                      .' you have already reached the maximum storage size of '
                      . dd_clip_format_size_units($size_limit) . '. '
                      .'. Please delete some clips in your clip bank or '
                      .'<a style="text-decoration: underline;" href="'
                      .$upgrade_link
                      .'">upgrade your membership</a>.');

    }

    $videos = explode(",", $form_data['videos_rendered']);
    $clips_info = VideoBuilderForm::collectClipInfo($videos);
    $transcript = array();
    $clips_field = array();
    array_push($transcript, array('value' => json_encode($clips_info['utters'])));
    array_push($clips_field, array('value' => json_encode($clips_info['clips'])));

    $new_tags = CommonHelper::getTags(explode(',', $form_data['video_tags']));

    $result = CommonHelper::uploadVideo($form_data['videoid']);
    if (!$result || !isset($result['video_url'])) {
      return CommonHelper::ajaxErrorResponse(
               "An error occurred while saving the clip (s3 error).");
    }

    $result = dd_clip_create_node($form_data['videoid'],
                                  $form_data['clip_name'],
                                  $form_data['note'],
                                  $form_data['commentary'],
                                  $form_data['duration'],
                                  $form_data['size'],
                                  $transcript,
                                  $clips_field,
                                  isset($form_data['display_transcript'])
                                    && $form_data['display_transcript'] ? 1 : 0,
                                  $new_tags);
    if (!$result || $result == -1) {
      return CommonHelper::ajaxErrorResponse(
               "An error occurred while saving the clip (database error).");
    }

    $response = new AjaxResponse();
    $response->addCommand(new AlertCommand(
      'Your clip [' . $form_data['clip_name'] . '] has been saved'));

    $response->addCommand(new InvokeCommand(
      NULL, "reloadPage", array()));
    
    return $response;
  }

  /**
   * Build Transcript ajax method. Constructs transcript for clip given begin and end times
   *
   * @param array $form 
   *   array of form elements
   * @param FormStateInterface $form_state 
   *   object representing form state
   *
   * @return object 
   *   AjaxResponse object passed back to browser 
   */
  public static function buildTranscriptCallback(array &$form, FormStateInterface $form_state) {
    $form_data = $form_state->getValues();
    $user = \Drupal::currentUser();
    $videos = preg_split("/,/", $form_data['videos_rendered']);
    $utters = array();
    $end_last = 0;

    foreach ($videos as $video) {
      $node = CommonHelper::getClipByVideoId($video);
      if ($node) {
        foreach (json_decode($node->field_transcript->first()->value) as $utter) {
          $utter->start = $utter->start + $end_last;
          $utter->end = $utter->end + $end_last;
          array_push($utters, $utter);
        }
        $end_last = end($utters)->end;
      }
    }
    $transcript = json_encode($utters);
    $response = new AjaxResponse();
    $response->addCommand(
      new InvokeCommand(NULL, 'appendTranscript', array($transcript)));
    return $response;
  }

  /**
   * a helper function to collect values in transcript field from each clip
   *   to be combined 
   *
   * @param array $videos 
   *   array of clip file ids 
   *
   * @return object 
   *   array of two arrays: utters for array of utterance info
   *                        clips for array of clip info 
   */
  private static function collectClipInfo($videos) {
    $utters = array();
    $clips = array();
    $end_last = 0;

    //loop through this to save values of all clips in order
    foreach ($videos as $video) {
      $node = CommonHelper::getClipByVideoId($video);
      if ($node != FALSE) {
        foreach (json_decode($node->field_clips->first()->value) as $clip) {
          $clip->start = $clip->start + $end_last;
          $clip->end = $clip->end + $end_last;
          array_push($clips, $clip);
        }
        foreach (json_decode($node->field_transcript->first()->value) as $utter) {
          $utter->start = $utter->start + $end_last;
          $utter->end = $utter->end + $end_last;
          array_push($utters, $utter);
        }
        $end_last = end($utters)->end;
      }
    }
    return array('utters' => $utters, 'clips' => $clips);
  }
}
