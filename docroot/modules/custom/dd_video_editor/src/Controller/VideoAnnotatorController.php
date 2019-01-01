<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Controller\VideoAnnotatorController.
 */

namespace Drupal\dd_video_editor\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * controller for VideoAnnotator page.
 */
class VideoAnnotatorController extends ControllerBase {

  private $videoId;
  private $clip;

  /**
   * generates video annotator page.
   *
   * @param array $video_id
   *    arguments passed as a part of url. Defined in routing file.
   *
   * @return array
   *    renderable array passed to twig template 
   */
  public function generateContent($clip_id) {
    $this->setMembers($clip_id);
    if (!$this->clip) {
      $msg = '<p class="dd-no-clip-loaded">' .
            'To annotate a clip, please go to the ' .
            '<a href="/video_editor/my_clip_bank">My Clip Bank</a>' .
            ' and click "Annotate" icon on a clip.</p>';
      return array(
        '#theme' => 'dd-video-annotator',
        '#title' => '',
        '#form' => $msg,
      );
    }

    $build_args = $this->generateBuildArgs();
    $form = \Drupal::formBuilder()
              ->getForm('Drupal\dd_video_editor\Form\VideoAnnotatorForm', $build_args);

    $video_file = CommonHelper::$s3url
                  . $this->videoId . '/' . $this->videoId . '.mp4';
    $thumbnail = CommonHelper::$s3url 
                  . $this->videoId . '/thumbnails/large.jpg';
    $video_type = "video/mp4";
    $clip_date = date('F jS, Y ', $this->clip->getCreatedTime());
    $annotations = isset($this->clip->field_annotations[0]) ?
      $this->clip->field_annotations->first()->value : '[]';
    $element = array(
      '#theme' => 'dd-video-annotator',
      '#title' => '',
      '#form' => $form,
      '#video_id' => $this->videoId,
      '#video_file' => $video_file,
      '#video_type' => $video_type,
      '#thumbnail' => $thumbnail,
      '#video_title' => ucwords($this->clip->getName()),
      '#clip_date' => $clip_date,
      '#annotations_json' => $annotations,
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
  private function setMembers($clip_id) {
    $this->videoId = $clip_id;
    $this->clip = CommonHelper::getClipByVideoId($clip_id);
  }

  /**
   * generate args passed to form builder.
   *
   * @return array
   *    array arguments for building annotator form 
   */
  private function generateBuildArgs() {
    $build_args = array();
    $build_args['nid'] = $this->clip->id();
    $build_args['video_id'] = $this->videoId;
    return $build_args;
  }
}

