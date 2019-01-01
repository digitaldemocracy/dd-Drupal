<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Controller\VideoClipperController.
 */

namespace Drupal\dd_video_editor\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * controller for VideoClipper page.
 */
class VideoClipperController extends ControllerBase {

  private $isClip;
  private $hid;
  private $videoId;
  private $startTime;
  private $endTime;
  private $video;
  private $clip;
  private $clips;

  /**
   * generates video clipper page.
   *
   * @param array $arg1
   *    arguments passed as a part of url. Defined in routing file.
   *
   * @return array
   *    renderable array passed to twig template 
   */
  public function generateContent($arg1) {
    parse_str($arg1, $args);
    $this->setMembers($args);
    if (!$this->videoId) {
      $msg = '<p class="dd-no-clip-loaded">' .
            'To begin editing a hearing video, please go to the ' .
            '<a href="/hearings">hearings page</a>' .
            ' and select a new hearing video, then click "Clip This".</p>';
      return array(
        '#theme' => 'dd-video-clipper',
        '#title' => '',
        '#form' => $msg,
      );
    }

    $build_args = $this->generateBuildArgs();
    $myform = \Drupal::formBuilder()
              ->getForm('Drupal\dd_video_editor\Form\VideoClipperForm', $build_args);

    $video_file = CommonHelper::$s3url
                  . $this->videoId . '/' . $this->videoId . '.mp4';
    $thumbnail = CommonHelper::$s3url 
                 . $this->videoId . '/thumbnails/large.jpg';
    $video_type = "video/mp4";
    $clip_date = $this->isClip ? date('F jS, Y ', $this->clip->getCreatedTime()) : Null;
    $time_vals = $this->generateTimeVals();
    $clip_source = $this->generateClipSource(); 
    $element = array(
      '#theme' => 'dd-video-clipper',
      '#title' => '',
      '#form' => $myform,
      '#video_id' => $this->videoId,
      '#video_file' => $video_file,
      '#video_type' => $video_type,
      '#thumbnail' => $thumbnail,
      '#video_title' => $build_args['video_title'],
      '#clip_date' => $clip_date,
      '#time' => $time_vals,
      '#source' => $clip_source,
    );
    return $element;
  }

  /**
   * set member variables.
   *
   * @param array $arg
   *    parsed arguments passed as a part of url. Defined in routing file.
   *
   */
  private function setMembers($args) {
    $this->hid = isset($args['hid']) ? $args['hid'] : Null;
    $this->isClip = isset($args['clip']) ? 1 : 0;
    if ($this->isClip) {
      $this->videoId = $args['clip'];
      $this->clip = CommonHelper::getClipByVideoId($args['clip']);
    } elseif (isset($args['vid'])) {
      $this->videoId = $args['vid'];
      $this->video = CommonHelper::getVideoByFileId($args['vid']);
    } elseif ($this->hid) {
      $this->videoId = current(CommonHelper::getVideoByHid($this->hid))->getFileId();
      $this->video = CommonHelper::getVideoByFileId($this->videoId);
    } else {
      $this->videoId = Null;
      $this->video = Null;
    }
    $this->startTime = isset($args['startTime']) ? ($this->isClip ? 0 :
                         ($args['startTime'] - 5 > 0 ? $args['startTime'] - 5
                           : $args['startTime'])) : ($this->isClip ? 0 : 0);
    $this->endTime = $this->isClip ? $this->clip->field_duration_float->first()->value 
                    : (isset($args['startTime']) ? ($args['startTime'] + 15 < $this->video->getDuration()
                    ? $args['startTime'] + 15 : $args['startTime']) : $this->startTime + 1);
    $this->clips = $this->isClip ? $this->clip->field_clips->first()->value : Null;
  }

  /**
   * generate args passed to form builder.
   *
   * @return array
   *    array arguments for building clipper form 
   */
  private function generateBuildArgs() {
    $build_args = array();
    $build_args['hid'] = $this->hid;
    $build_args['is_clip'] = $this->isClip;
    $build_args['video_id'] = $this->videoId;
    $build_args['vid'] = $this->isClip ? Null : $this->video->id();
    $build_args['video_title'] = $this->isClip ? ucwords($this->clip->getName())
                                   : CommonHelper::generateHearingTitle($this->hid);
    $build_args['start_time'] = $this->startTime;
    $build_args['end_time'] = $this->endTime;
    $build_args['duration'] = $this->isClip ? $this->clip->field_duration_float->first()->value
                                            : $this->video->getDuration(); 
    $build_args['clips'] = $this->isClip ? $this->clip->field_clips->first()->value : Null; 
    return $build_args;
  }

  /**
   * generate clip timing values passed to form builder.
   *
   * @return array
   *    array clip timing values for building clipper form 
   */
  private function generateTimeVals() {
    $time_vals = array();
    $time_vals['start_min'] = floor($this->startTime/60);
    $time_vals['start_sec'] = floor($this->startTime % 60);
    $time_vals['end_min'] = floor($this->endTime / 60);
    $time_vals['end_sec'] = floor($this->endTime % 60);
    return $time_vals;
  }

  /**
   * generate url path to source video of the clip.
   *
   * @return string 
   *     path string or Null if the video is not a clip
   */
  private function generateClipSource() {
    if ($this->isClip && count(json_decode($this->clips)) == 1) {
      $clip = json_decode($this->clips)[0];
      $path = 'hid=' . $clip->hid . '&startTime=' . $clip->vid_start .
              '&vid=' . $clip->vid;
      return $path;
    }
    return Null;
  }
}

