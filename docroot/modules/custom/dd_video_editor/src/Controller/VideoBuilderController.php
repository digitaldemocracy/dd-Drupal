<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Controller\VideoBuilderController.
 */

namespace Drupal\dd_video_editor\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * controller for VideoBuilder page.
 */
class VideoBuilderController extends ControllerBase {

  /**
   * generates video builder page.
   *
   * @return array
   *    renderable array passed to twig template 
   */
  public function generateContent() {

    $form = \Drupal::formBuilder()
              ->getForm('Drupal\dd_video_editor\Form\VideoBuilderForm');
    $combine_form = \Drupal::formBuilder()
              ->getForm('Drupal\dd_video_editor\Form\VideoCombineForm');

    $element = array(
      '#theme' => 'dd-video-builder',
      '#title' => 'Video Builder',
      '#form' => $form,
      '#combine_form' => $combine_form,
    );
    return $element;
  }
}

