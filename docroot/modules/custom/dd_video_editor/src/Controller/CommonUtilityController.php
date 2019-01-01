<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Controller\CommonUtilityController.
 */

namespace Drupal\dd_video_editor\Controller;
use Drupal\Core\Controller\ControllerBase;
use \Drupal\dd_video_editor\Utility\CommonHelper;
use \Drupal\dd_clip\Entity\DdVideoTags;

/**
 * controller for common utility purposes.
 */
class CommonUtilityController extends ControllerBase {

  /**
   * implements tag name auto complete  
   *
   * @param string $vocab_name
   *   vocaburary name 
   *
   * @return json 
   *    json list of matched tag names 
   */
  public static function videoTagAutocomplete() {
    $key=\Drupal::request()->get('q');
    $user = \Drupal::currentUser();
    $objs = DdVideoTags::loadByFields([['field'=>'tag','value'=>$key.'%','op'=>'like'],
                                       ['field'=>'user_id','value'=>$user->id()]]);
    $tags = array();
    foreach ($objs as $obj) {
      $tags[] = $obj->getTag();
    }
    $tags = array_unique($tags);
    natcasesort($tags);
    $matches = array();
    foreach($tags as $tag) {
      $matches[] = array('value' => $tag, 'label' => $tag);
    }
    return new \Symfony\Component\HttpFoundation\JsonResponse($matches);
  }

  /**
   * ajax call entry point for updating my clip bank block  
   *
   * @return array 
   *    html of my clip bank block 
   */
  public static function myClipBankBlock() {
    return CommonHelper::renderClipperGalleryBlock();
  }

  /**
   * Output Clip Select Block.
   *
   * @return array
   *   html of clip select block
   */
  public static function clipSelectBlock() {
    return CommonHelper::renderClipSelectBlock();
  }
  /**
   * Output Member Clips Block.
   *
   * @return array
   *   html of member clips block
   */
  public static function memberClipsBlock() {
    return CommonHelper::renderMemberClipsBlock();
  }

  /**
   * receives ajax POST call
   * and publishes a node specified by the call
   *
   * @param string $clip_id
   *   clip's video file id   
   */
  public static function setVideoStatus($clip_id) {
    $node = CommonHelper::getClipByVideoId($clip_id);
    if ($node) {
      if (!$node->isPublished()) {
        $node->setPublished(True);
      } else {
        $node->setPublished(False);
      }
      $node->save();
      return new \Symfony\Component\HttpFoundation\Response('success');
    }
    return new \Symfony\Component\HttpFoundation\Response('fail');
  }

  /**
   * receives ajax POST call
   * and returns the value of annotations field
   * of the clip specified by the call
   *
   * @param string $clip_id
   *   clip's video file id   
   */
  public static function getAnnotations() {
    $content = \Drupal::request()->getContent();
    if ($content) {
      parse_str($content, $data);
      $clip_id = trim($data['clip']);
    } else {
      return new \Symfony\Component\HttpFoundation\JsonResponse('fail');
    }
    $node = CommonHelper::getClipByVideoId($clip_id);
    if ($node) {
      $annotations = $node->field_annotations->first()
        ? $node->field_annotations->first()->value : '[]';
      return new \Symfony\Component\HttpFoundation\JsonResponse($annotations);
    }
    return new \Symfony\Component\HttpFoundation\JsonResponse('fail');
  }
}

