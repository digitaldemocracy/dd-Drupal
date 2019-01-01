<?php
/**
 * @file
 * Contains Drupal\dd_video_editor\Utility\CommonHelper.
 */

namespace Drupal\dd_video_editor\Utility;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\OpenDialogCommand;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\dd_utterance\Entity\DdCurrentUtterance;
use Drupal\dd_video\Entity\DdVideo;
use Drupal\dd_clip\Entity\DdClip;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\dd_clip\Entity\DdVideoTags;
use Drupal\dd_clip\Entity\DdClipBankQuota;

/**
 * contains public helper functions common to video editor module
 * also contains some constant values used by all video editor module
 */
class CommonHelper {

  public static $s3url =
    'https://s3-us-west-2.amazonaws.com/videostorage-us-west/videos/';

  public static $restUrl = "http://clip.digitaldemocracy.org/api/v1.0/video_editor/";

  /**
   * Function to get dd_clip node by video file id.
   *
   * @param string $file_id
   *   video file id of the clip
   *
   * @return object
   *   dd_clip node object
   */
  public static function getClipByVideoId($file_id) {
    $field_vals = array(
      array('field' => 'field_videoid', 'value' => $file_id),
    );
    return current(DdClip::loadByFields($field_vals));
  }

  /**
   * Function to get file size of clip.
   *
   * @param string $file_id
   *   video file id of the clip
   *
   * @return string
   *   string of digits representing the file size in bytes
   */
  public static function getClipSize($file_id) {
    $clip = CommonHelper::getClipByVideoId($file_id);
    return $clip->field_file_size->first()->value;
  }

  /**
   * Function to get all the clips owned by the user.
   *
   * @param string $user
   *   user object
   * @param bool $show_private
   *   If FALSE, will only show shared clips
   *
   * @return array
   *   array of dd_clip node objects
   */
  public static function getClipsByUser($user, $show_private = TRUE) {
    $field_vals = array(
      array('field' => 'user_id', 'value' => $user->id()),
    );

    if (!$show_private) {
      $field_vals[] = ['field' => 'status', 'value' => 1];
    }
    return DdClip::loadByFields($field_vals);
  }

  /**
   * Function to get all the clips.
   *
   * @param bool $show_private
   *   If FALSE, will only show shared clips
   *
   * @return array
   *   array of dd_clip node objects
   */
  public static function getAllClips($show_private = TRUE) {
    $field_vals = [];

    if (!$show_private) {
      $field_vals[] = ['field' => 'status', 'value' => 1];
    }
    return DdClip::loadByFields($field_vals);
  }

  /**
   * Function to get video entity by file id.
   *
   * @param string $file_id
   *   video file id
   *
   * @return object
   *   DdVideo Entity object
   */
  public static function getVideoByFileId($file_id) {
    $field_vals = array(
      array('field' => 'fileId', 'value' => $file_id),
    );
    return current(DdVideo::loadByFields($field_vals));
  }

  /**
   * Function to get video entity by hid.
   *
   * @param string $hid
   *   video file id
   *
   * @return array of object
   *   Array of DdVideo Entity object
   */
  public static function getVideoByHid($hid) {
    $field_vals = array(
      array('field' => 'hid', 'value' => $hid),
    );
    $order_bys = array(
      array('field' => 'position', 'dir' => 'asc')
    );
    return DdVideo::loadByFields($field_vals, $order_bys);
  }

  /**
   * Function to get committee objects by hid.
   *
   * @param int $hid
   *   hearing id
   *
   * @return array
   *   array of DdCommittee entity objects
   */
  public static function getCommitteeByHid($hid) {
    $cids = DdHearing::getCommitteeIdsForHearing($hid);
    return DdCommittee::loadMultiple($cids);
  }

  /**
   * Function to generate hearing title.
   *
   * @param int $hid
   *   hearing id
   *
   * @return string
   *   hearing title in the format of <committee name> hearing of <hearing date>
   */
  public static function generateHearingTitle($hid) {
    $committees = CommonHelper::getCommitteeByHid($hid);
    $comm_names = array();
    foreach ($committees as $comm) {
      $comm_names[] = $comm->getName();
    }
    $hearing = DdHearing::load($hid);
    return implode(' & ', $comm_names) . ' Hearing: '
      . date('m-d-Y', $hearing->getDate());
  }

  /**
   * Function to get person name by pid.
   *
   * @param int $pid
   *   person id
   *
   * @return string
   *   person name in the form of <first> <middle> <last>
   */
  public static function getPersonNameByPid($pid) {
    $person = DdPerson::load($pid);
    $name = $person->getMiddleName() ?
      $person->getFirstName() . ' ' . $person->getMiddleName()
      . ' ' . $person->getLastName() : $person->getFirstName() . ' '
      . $person->getLastName();
    return $name;
  }

  /**
   * Function to get current utterance by vid.
   *
   * @param int $vid
   *   video id (not file id)
   *
   * @return array
   *   array of DdCurrentUtterance objects
   */
  public static function getCurrentUtteranceByVid($vid) {
    $field_vals = array(
      array('field' => 'vid', 'value' => $vid),
    );
    return DdCurrentUtterance::loadByFields($field_vals);
  }

  /**
   * Function to get current utterance by field values.
   *
   * @param array $fields_vals
   *   array of field name with values
   * @param array $order_bys
   *   array of field name with direction (ASC or DESC) to sort query result
   *
   * @return array
   *   array of DdCurrentUtterance objects
   */
  public static function getCurrentUtteranceByFields(array $field_vals, array $order_bys) {
    return DdCurrentUtterance::loadByFields($field_vals, $order_bys);
  }

  /**
   * Function to get total clip storage usage by the current user.
   *
   * @return int
   *   total size in bytes
   */
  public static function getUsage() {
    $user = \Drupal::currentUser();
    $used = 0;

    $clips = CommonHelper::getClipsByUser($user);
    foreach ($clips as $clip) {
      if ($clip->field_file_size->first()) {
        $used += (int) $clip->field_file_size->first()->value;
      }
    }
    return $used;
  }

  /**
   * Function to get quota size of the current user.
   *
   * @return int
   *   quota size in bytes
   */
  public static function getQuota() {
    $user = \Drupal::currentUser();
    $quota = 0;
    $nodes = DdClipBankQuota::loadByFields([['field'=>'user_id','value'=>1,'op'=>'>=']]);
    foreach ($nodes as $node) {
      if (array_search(
          $node->getName(), $user->getRoles()) !== FALSE
      ) {
        return (int) $node->field_quota_in_bytes->first()->value;
      }
    }

    $nodes = DdClipBankQuota::loadByFields([['field'=>'name','value'=>'regular']]);
    if ($nodes && count($nodes) > 0) {
      $node = current($nodes);
      return (int) $node->field_quota_in_bytes->first()->value;
    }
    return $quota;
  }

  /**
   * Function to make a rest api call to cut video.
   *
   * @param int $user_id
   *   user id is used as a salt on the server
   * @param string $videoid
   *   video file id
   * @param int $start
   *   cut start position in seconds
   * @param int $end
   *   cut end position in seconds
   *
   * @return array
   *   array which includes file size, clip file id
   */
  public static function cutVideo($user_id, $videoid, $start, $end) {
    if ($end <= $start) {
      return 'The end time must be later than the start time.';
    }
    // Was 600.
    $context_options = array(
      'http' => array(
        'method' => 'POST',
        'timeout' => 1200,
      ),
    );
    $duration = $end - $start;

    // Video = video file id (file name without extension).
    $data = array(
      'video' => $videoid,
      'start' => $start,
      'length' => $duration,
      'uid' => $user_id,
      're_encode' => 1,
    );
    $data = json_encode($data);
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";

    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl . "cut";
    $json_result = file_get_contents($request_url, FALSE, $context);
    return json_decode($json_result, TRUE);
  }

  /**
   * Function to make a rest api call to concat clips.
   *
   * @param int $user_id
   *   user id is used as a salt on the server
   * @param array $videos
   *   list of video file ids to concat
   *
   * @return array
   *   array which includes file size, clip file id
   */
  public static function concatVideos($user_id, $videos) {
    $context_options = array(
      'http' => array(
        'method' => 'POST',
        'timeout' => 1200,
      ),
    );
    $data = array('uid' => $user_id, 'videos' => $videos, 'transition' => 1);
    $data = json_encode($data);
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";
    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl . "concat";
    $json_result = file_get_contents($request_url, FALSE, $context);
    return json_decode($json_result, TRUE);
  }

  /**
   * Function to make a rest api call to upload clip to s3.
   *
   * @param string $videoid
   *   file id of clip to upload
   *
   * @return array
   *   array which includesi url of thumbnail files, video file
   */
  public static function uploadVideo($videoid) {
    $context_options = array(
      'http' => array('method' => 'POST', 'timeout' => 1200),
    );
    $data = array('video_id' => $videoid);
    $data = json_encode($data);
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";
    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl . "upload";
    $json_result = file_get_contents($request_url, FALSE, $context);
    $result = json_decode($json_result, TRUE);
    return $result;
  }

  /**
   * Function to build transcript associated with a clip.
   *
   * @param int $hid
   *   id of hearing associated with the clip
   * @param string $videoid
   *   file id of video associated with the clip
   * @param int $clip_start
   *   start position of clip in sec
   * @param int $clip_end
   *   end position of clip in sec
   *
   * @return string
   *   json string representing transcript objects
   */
  public static function videoBuildTranscript($hid, $videoid, $clip_start, $clip_end) {
    $utters = array();
    // Find all utterances with hid, vid and start before the end of the clip.
    $video = CommonHelper::getVideoByFileId($videoid);
    $fields_vals = array(
      0 => array('field' => 'vid', 'value' => $video->id()),
      1 => array('field' => 'time', 'value' => $clip_end, 'op' => '<'),
    );
    $order_bys = array(array('field' => 'time', 'dir' => 'asc'));
    $nodes = CommonHelper::getCurrentUtteranceByFields($fields_vals, $order_bys);
    if ($nodes) {
      // For each dd_utterance.
      foreach ($nodes as $utter) {
        $start = $utter->getTime();
        $end = $utter->getEndTime();
        if ($start >= $clip_start && $start < $clip_end
          || $end > $clip_start && $end <= $clip_end
          || $start < $clip_start && $end > $clip_end
        ) {

          $pid = (int) $utter->pid->getValue()[0]['target_id'];
          $speaker = CommonHelper::getPersonNameByPid($pid);
          array_push($utters, array(
            'start' => max(0, $start - $clip_start),
            'end' => min($clip_end - $clip_start, $end - $clip_start),
            'vid_start' => $start,
            'vid_end' => $end,
            'text' => $utter->getText(),
            'speaker' => $speaker,
            'pid' => $pid,
            'hearing' => CommonHelper::generateHearingTitle($hid),
            'hid' => (int) $hid,
            'vid' => $videoid,
          ));
        }
      }
    }
    return json_encode($utters);
  }

  /**
   * A helper function to get taxonomy term object by name.
   *
   * @param string $name
   *   name of term
   * @param string $vocab_name
   *   name of vocabruary
   *
   * @return object
   *   taxonomy term object
   */
  public static function taxonomyGetTermByName($name, $vocab_name) {
    $user = \Drupal::currentUser();
    $tags = DdVideoTags::loadByFields([['field'=>'tag','value'=>$name],
                                       ['field'=>'user_id','value'=>$user->id()]]);
    if ($tags && count($tags) > 0) {
      return current($tags);
    }
    return NULL;
  }

  /**
   * A helper function to get taxonomy term object by name.
   *
   * If the term does not exists a new term will be created.
   *
   * @param string $tag
   *   name of term
   * @param string $vocab_name
   *   name of vocabruary
   *
   * @return object
   *   taxonomy term object
   */
  public static function getOrCreateTagTerm($tag, $vocab_name) {
    $user = \Drupal::currentUser();
    $term = CommonHelper::taxonomyGetTermByName($tag, $vocab_name);
    if (!$term) {
      // Otherwise add new term.
      $term = DdVideoTags::create(
        array('tag' => $tag, 'user_id' => $user->id()));
      $term->save();
    }
    return $term;
  }

  /**
   * A helper function to get taxonomy term objects by name from video_tags.
   *
   * @param array $tags
   *   array of names of term
   *
   * @return array
   *   taxonomy term object
   */
  public static function getTags(array $tags) {
    $new_tags = array();
    $processed_tags = array();
    // For each tag.
    $tags = array_unique($tags);
    natcasesort($tags);
    foreach ($tags as $tag) {
      $tag = trim($tag);
      if ($tag && !in_array($tag, $processed_tags)) {
        $term = CommonHelper::getOrCreateTagTerm($tag, 'video_tags');
        $new_tags[] = array('target_id' => $term->id());
        $processed_tags[] = $tag;
      }
    }
    return $new_tags;
  }

  /**
   * A helper function to get all tags associated with clips.
   *
   * @param array $clips
   *   array of dd_clip objects
   *
   * @return array
   *   array of tag names
   */
  public static function getClipTags($clips) {
    $tags = array();
    foreach ($clips as $node) {
      foreach ($node->field_video_tags->getValue() as $term) {
        $tag = DdVideoTags::load($term['target_id'])
          ->getTag();
        $tags[] = $tag;
      }
    }
    $tags = array_unique($tags);
    return $tags;
  }

  /**
   * Helper function to build representation of tags used in my clip bank block.
   *
   * @param array $tags
   *   array of names
   *
   * @return array
   *   array of modified tag name strings
   */
  public static function buildClipRepTags($tags) {
    $rep_tags = array();
    foreach ($tags as $tag) {
      $rep_tag = str_replace('-', '--', $tag);
      $rep_tag = str_replace(' ', '-', $rep_tag);
      $rep_tags[$tag] = $rep_tag;
    }
    return $rep_tags;
  }

  /**
   * A helper function to get all the clip tag names used by the current user.
   *
   * @return array
   *   array of tag name strings
   */
  public static function getClipUserTags() {
    // Get all tags for user.
    $nodes = CommonHelper::getClipsByUser(\Drupal::currentUser());
    $tags = CommonHelper::getClipTags($nodes);
    return $tags;
  }

  /**
   * A helper function to build see my tags markuo.
   *
   * @return string
   *   html markup
   */
  public static function buildUserTags() {
    // Get all tags for user.
    $user_tags = '<div id="vc-user-tags">'
      . '<a href="#">See my tags</a>'
      . '<div id="vc-user-tags-container"></div>'
      . '</div>';
    return $user_tags;
  }

  /**
   * A helper function to build array of assoc between clip id, name, and tags.
   *
   * @param array $nodes
   *   Array of nodes.
   *
   * @return array
   *   array of association between clip id, name, and tags
   */
  public static function buildClipTags($nodes) {
    // drupal_add_library('system', 'ui.dialog');
    $clips = array();

    foreach ($nodes as $node) {
      $tags = CommonHelper::getClipTags(array($node));
      $clips[$node->id()] = array(
        'name' => $node->getName(),
        'tags' => $tags,
      );
    }
    return $clips;
  }

  /**
   * A helper function to generate html markup for my clip bank block.
   *
   * @return string
   *   html markup
   */
  public static function renderClipperGalleryBlock() {
    $block_manager = \Drupal::service('plugin.manager.block');
    // You can hard code configuration or you load from settings.
    $config = [];
    $plugin_block = $block_manager->createInstance('clipper_gallery_block', $config);
    return $plugin_block->build();
  }

  /**
   * A helper function to generate html markup for Clip Select block.
   *
   * @return string
   *   html markup
   */
  public static function renderClipSelectBlock() {
    $block_manager = \Drupal::service('plugin.manager.block');
    // You can hard code configuration or you load from settings.
    $config = [];
    $plugin_block = $block_manager->createInstance('clip_select_block', $config);
    return $plugin_block->build();
  }

  /**
   * A helper function to generate html markup for Member Clips block.
   *
   * @return string
   *   html markup
   */
  public static function renderMemberClipsBlock() {
    $block_manager = \Drupal::service('plugin.manager.block');
    // You can hard code configuration or you load from settings.
    $config = [];
    $plugin_block = $block_manager->createInstance('member_clips_block', $config);
    return $plugin_block->build();
  }

  /**
   * A helper function to make a message to popup on user's browser.
   *
   * @param string $msg
   *   message to show
   *
   * @return string
   *   AjaxResponse object
   */
  public static function ajaxErrorResponse($msg) {
    $response = new AjaxResponse();
    #return $response->addCommand(new AlertCommand($msg));
    return $response->addCommand(new OpenDialogCommand('#video-editor-message','Error',$msg));
  }

  /**
   * A helper function to check if the user has certain roles.
   *
   * @param string $permission_string
   *   permission string
   *
   * @return bool
   *   true if the user has the permission, otherwise false
   */
  public static function isPermitted($permission_string) {
    $user = \Drupal::currentUser();
    return User::load($user->id())->hasPermission($permission_string);
  }

  /**
   * A helper function to check if the user has annotation priv.
   *
   * @return bool
   *   true if the user has the priv, otherwise false
   */
  public static function userHasAnnoPriv() {
    return CommonHelper::isPermitted('access dd video annotator');
  }

  /**
   * A helper function to check if the user is the owner of clip.
   *
   * @param string $clip_id
   *   video id (file id) of the clip
   *
   * @return bool
   *   true if the user is the owner, otherwise false
   */
  public static function userIsClipOwner($clip_id) {
    $node = CommonHelper::getClipByVideoId($clip_id);
    if ($node) {
      $user = \Drupal::currentUser();
      return $node->getOwnerId() === $user->id();
    }
    else {
      return FALSE;
    }
  }

  /**
   * A helper function to check if the user should be able to annotate the clip.
   *
   * @param string $clip_id
   *   video id (file id) of the clip
   *
   * @return bool
   *   true if the user should be able to, otherwise false
   */
  public static function userCanAnnotateClip($clip_id) {
    return CommonHelper::userIsClipOwner($clip_id)
      && CommonHelper::userHasAnnoPriv();
  }

  /**
   * A helper function to format seconds to string.
   *
   * @param float $sec_float
   *   seconds in float 
   *
   * @return string 
   *   string in the form of h:mm:ss.ms
   */
  public static function formatSeconds($sec_float) {
    $h = floor($sec_float / 3600);
    $sec_float = $sec_float % 3600;
    $m = str_pad(strval(floor($sec_float / 60)),2,"0",STR_PAD_LEFT);
    $s = $sec_float % 60;
    $pos = strpos(strval($s),".");
    if ($pos) {
      $s = str_pad(substr(strval($s),0,$pos),2,"0",STR_PAD_LEFT).substr(strval($s),$pos);
    } else {
      $s = str_pad(strval($s),2,"0",STR_PAD_LEFT);
    }
    return $h . ":" . $m . ":" . $s;
  }
}
