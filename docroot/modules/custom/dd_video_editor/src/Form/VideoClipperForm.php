<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Form\VideoClipperForm.
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
use \Drupal\dd_clip\Entity\DdClip;
use Drupal\Core\Url;

/**
 * Contribute form for clipping video.
 */
class VideoClipperForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_editor_video_clipper_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $build_args = $form_state->getBuildInfo()['args'][0];
    
    $form['is_clip'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['is_clip'],
    );

    // video file id
    $form['videoid'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['video_id'],
    );

    // primary key of video 
    $form['vid'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['vid'],
    ); 

    $form['hid'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['is_clip'] ? null : $build_args['hid'],
    );

    $form['video_title'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['video_title'],
    );
    
    $form['duration'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['duration'],
    );

    $form['hide'] = array(
      '#type' => 'fieldset',
      '#prefix' => '<div class="hide">',
      '#suffix' => '</div>',
    );

    $form['hide']['start'] = array(
      '#type' => 'textfield',
      '#size' => 20,
      '#default_value' => $build_args['start_time'],
    );

    $form['hide']['end'] = array(
      '#type' => 'textfield',
      '#size' => 20,
      '#default_value' => $build_args['end_time'],
    );

    $form['body'] = array(
      '#type' => 'fieldset',
    );
 
    $form['body']['clip_name'] = array(
      '#type' => 'textfield',
      '#title' => 'Clip Name *',
      '#size' => 20,
    );

    $form['body']['note'] = array(
      '#type' => 'textarea',
      '#title' => 'Private Note',
      '#size' => 255,
    );
    
    if (CommonHelper::isPermitted('attach transcripts to clips')) {
      $form['body']['commentary'] = array(
        '#type' => 'text_format',
        '#default_value' => '',
        '#title' => 'Commentary',
      );

      $form['body']['insert_transcript'] = array(
        '#type' => 'submit',
        '#ajax' => array(
          'callback' => '\Drupal\dd_video_editor\Form\VideoClipperForm::buildTranscriptCallback',
        ),
        '#value' => 'Append transcript',
      );

      $form['body']['transcript'] = array(
        '#type' => 'checkbox',
        '#title' => 'Display Transcript',
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
          '\Drupal\dd_video_editor\Form\VideoClipperForm::submitFormCallback',
      ),
      '#value' => 'Create clip',
      '#prefix' => '<div class="vc-save">',
      '#suffix' => '</div>',
    );
    
    if (!$build_args['is_clip']) {
      $form['actions']['hearing_link']=array(
        '#markup' => '<div class="vc-full-hearing"><a href="' . $base_url
                     . '/hearing/' . $build_args['hid'] . '?vid='
                     . $build_args['video_id']
                     . '&startTime=' . $build_args['start_time'] . 
                     '">Back to Hearing</a></div>'
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

    // do not allow clip save if the title is not filled in 
    if (!isset($form_data['clip_name']) || $form_data['clip_name'] === "") {
      return CommonHelper::ajaxErrorResponse(
               'Please fill in the "CLIP NAME" field.');
    }
    
    $size_limit = CommonHelper::getQuota();
    if ($size_limit >= 0 && $size_limit < CommonHelper::getUsage()) {
      return CommonHelper::ajaxErrorResponse(
                    'The clip could not be created because'
                    .' you have already reached the maximum storage size of '
                    . dd_clip_format_size_units($size_limit)
                    .'. Please delete some clips in your clip bank or '
                    .'<a style="text-decoration: underline;" href="'
                    . $upgrade_link
                    .'">upgrade your membership</a>.');
    }

    $begin = $form_data['start'];
    $end = $form_data['end'];

    if ($end <= $begin) {
      return CommonHelper::ajaxErrorResponse(
               'The end time must be later than the start time.');
    }

    $clip = CommonHelper::cutVideo($user->id(), $form_data['videoid'], $begin, $end);
    if (!$clip || !isset($clip['size'])) {
      return CommonHelper::ajaxErrorResponse( 
               "An error occurred while creating the clip. Error code: "
               . $clip
             );
    }

    // do not allow clip save if it exceeds the quata
    if ($size_limit >= 0 && $size_limit < (CommonHelper::getUsage() + $clip['size'])) {
      return CommonHelper::ajaxErrorResponse( 
               'The clip (size: '.dd_clip_format_size_units($clip['size'])
               .') could not be created because'
               .' you would exceed the maximum storage size of '
               . dd_clip_format_size_units($size_limit)
               .'. Please delete some clips in your clip bank or '
               .'<a style="text-decoration: underline;" href="'
               . $upgrade_link
               .'">upgrade your membership</a>.');

    }
    
    $uri_parts = explode("/", $clip['uri']);

    $file_name = $uri_parts[count($uri_parts) - 1];
    $videoid = explode(".", $file_name)[0];

    $transcript_clips = VideoClipperForm::getTranscript($form_data);
    $transcript = $transcript_clips[0];
    $clips = $transcript_clips[1];

    $new_tags = CommonHelper::getTags(explode(',', $form_data['video_tags']));

    $result = CommonHelper::uploadVideo($videoid);
    if (!$result || !isset($result['video_url'])) {
      return CommonHelper::ajaxErrorResponse(
               "An error occurred while saving the clip (s3 error).");
    }

    $result = dd_clip_create_node(
                $videoid,
                $form_data['clip_name'],
                $form_data['note'],
                $form_data['commentary'],
                $clip['duration'],
                $clip['size'],
                $transcript,
                $clips,
                isset($form_data['transcript']) && $form_data['transcript'] ? 1 : 0,
                $new_tags);

    if (!$result || $result == -1) {
      return CommonHelper::ajaxErrorResponse(
               "An error occurred while saving the clip (database error).");
    }
    $clip = DdClip::load($result);
    $response = new AjaxResponse();
    $response->addCommand(new AlertCommand(
      'Your clip [' . $form_data['clip_name'] . '] has been saved'));

    $rendered_block = CommonHelper::renderClipperGalleryBlock();
    $response->addCommand(
      new ReplaceCommand('#clipper-gallery-block', $rendered_block));

    $response->addCommand(new InvokeCommand(
      NULL, "showHideNote",
      array(dd_clip_format_size_units(CommonHelper::getUsage()))));
    $response->addCommand(new InvokeCommand(
      NULL, "setupTagClick"));

    return $response;
  }

  /**
   * a helper function for cutting transcript
   *
   * @param array $vals 
   *   array of objects representing clip 
   * @param int $clip_start 
   *   start of clip in sec 
   * @param int $clip_end 
   *   end of clip in sec 
   * @param boolean $change_vid 
   *   whether or not vid changed 
   *
   * @return object 
   *   new json objects representing clip 
   */  
  private static function clipCutTimings($vals, $clip_start, $clip_end, $change_vid) {
    $new_vals = array();
    $duration = $clip_end - $clip_start;

    foreach($vals as $val) {
      $start = $val->start;
      $end = $val->end;
      if ($start >= $clip_start && $start < $clip_end
        || $end > $clip_start && $end <= $clip_end
        || $start < $clip_start && $end > $clip_end) {
        $val->start = max(0, $start - $clip_start);
        $val->end = min($duration, $end - $clip_start);
        if ($change_vid) {
          if ($val->start == 0) {
            $val->vid_start += $clip_start;
          }
          if ($val->end == $duration) {
            $val->vid_end = $val->vid_start + $val->end - $val->start;
          }
        }
        array_push($new_vals, $val);
      }
    }

    return json_encode($new_vals);
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
  public static function buildTranscriptCallback(
    array &$form, FormStateInterface $form_state) {
    $form_data = $form_state->getValues();
    if ($form_data['is_clip']) {
      $node = CommonHelper::getClipByVideoId($form_data['videoid']);
      if ($node) {
        $transcript = VideoClipperForm::clipCutTimings(
           json_decode($node->field_transcript->getValue()), $form_data['start'],
                       $form_data['end'], false);
      }
    } else {
      $transcript = CommonHelper::videoBuildTranscript(
        $form_data['hid'], $form_data['videoid'],
        $form_data['start'], $form_data['end']);
    }

    $response = new AjaxResponse();
    $response->addCommand(
      new InvokeCommand(NULL, 'appendTranscript', array($transcript)));
    return $response; 
  }

  /**
   * a helper function to get transcript based on user specified cut points
   *
   * @param array $form_data 
   *   array of form field values 
   *
   * @return array 
   *   contains array of json objects of transcripts and clips 
   */ 
  public static function getTranscript(array $form_data) {
    $transcript = array();
    $clips = array();
    if ($form_data['is_clip']) {
      $node = CommonHelper::getClipByVideoId($form_data['videoid']);
      if ($node) {
        array_push($transcript, array(
          'value' => VideoClipperForm::clipCutTimings(
                       json_decode($node->field_transcript->getValue()),
                                   $form_data['start'], $form_data['end'], false)
        ));
        array_push($clips, array(
          'value' => VideoClipperForm::clipCutTimings(
                       json_decode($node->field_clips->getValue()),
                                   $form_data['start'], $form_data['end'], true)
        ));
      }
    } else {
      $node = \Drupal\dd_hearing\Entity\DdHearing::load($form_data['hid']);
      if ($node) {
        array_push($transcript, array(
          'value' => CommonHelper::videoBuildTranscript(
                       $form_data['hid'], $form_data['videoid'],
                       $form_data['start'], $form_data['end'])
        ));
        $clip = array(
          0 => array(
            'start' => 0,
            'end' => $form_data['end'] - $form_data['start'],
            'vid_start' => $form_data['start'],
            'vid_end' => $form_data['end'],
            'hid' => $form_data['hid'],
            'vid' => $form_data['videoid'],
            'hearing_title' => $form_data['video_title'],
          ),
        );
        array_push($clips, array('value' => json_encode($clip)));
      }
    }
    return array($transcript, $clips);
  }

}
?>
