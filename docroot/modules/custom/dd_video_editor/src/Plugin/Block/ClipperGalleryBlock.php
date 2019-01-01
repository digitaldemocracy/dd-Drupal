<?php

namespace Drupal\dd_video_editor\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use \Drupal\dd_video_editor\Utility\CommonHelper;

/**
 * Provides a 'Clip Gallery for Clipper' Block
 *
 * @Block(
 *   id = "clipper_gallery_block",
 *   admin_label = @Translation("Clip Gallery block for Clipper page"),
 * )
 */
class ClipperGalleryBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->generateBlockArray('clipper-gallery-item',
                                     'clipper-gallery-block');
  }

  /**
   * A helper function to generate array of block elements
   * Will be shared by children.
   *
   * @param string $item_theme
   *   theme name for each clip item
   * @param string $block_theme
   *   theme name for the whole block
   * @param bool $show_private
   *   If FALSE, will only show shared clips
   * @param bool $show_all_users
   *   If TRUE, will show clips from all users
   *
   * @return array
   *   Renderable Array of block.
   */
  protected function generateBlockArray($item_theme, $block_theme, $show_private = TRUE, $show_all_users = FALSE) {
    $variables = array();
    if ($show_all_users) {
      $nodes = CommonHelper::getAllClips($show_private);
    }
    else {
      $nodes = CommonHelper::getClipsByUser(\Drupal::currentUser(), $show_private);
    }
    $items = $this->generateClipGalleryItems($nodes, $item_theme);
    $tags = CommonHelper::getClipTags($nodes);
    natcasesort($tags);
    $rep_tags = CommonHelper::buildClipRepTags($tags);
    $clip_tags = CommonHelper::buildClipTags($nodes);
    $form = \Drupal::formBuilder()
            ->getForm('Drupal\dd_video_editor\Form\VideoTagsEditForm');
    $total_usage_size = dd_clip_format_size_units(CommonHelper::getUsage());
    $quota_size = dd_clip_format_size_units(CommonHelper::getQuota());

    return array(
      '#theme' => $block_theme,
      '#title' => 'My Clip Bank',
      '#total_usage_size' => $total_usage_size,
      '#quota_size' => $quota_size,
      '#items' => $items,
      '#rep_tags' => $rep_tags,
      '#clips_json' => json_encode($clip_tags),
      '#tags_csv' => implode(',', $tags),
      '#form' => $form,
      '#cache' => array(
        'max-age' => 0,
      ),
    );
  }

  /**
   * A helper function to generate array of each clip item 
   *
   * @param array $nodes
   *   array of clip nodes of the user 
   * @param string $theme
   *  theme name for each clip item 
   *
   * @return array
   *   Renderable Array of clip items.
   */
  protected function generateClipGalleryItems($nodes, $theme) {
    $items = array();
    foreach ($nodes as $row_num => $node) {
      $item = $this->generateClipGalleryItem($node, $row_num);
      $items[] = array(
        '#theme' => $theme,
        '#elements' => $item,
      );
    }
    return $items;
  }

  /**
   * A helper function to generate array of elements for a clip item 
   *
   * @param array $node
   *   a clip node object
   * @param int $row_num
   *   index in the array of clip nodes
   *   not in use but will be used for styling purposes
   *
   * @return array
   *   Array of elements for a clip item.
   */
  protected function generateClipGalleryItem($node, $row_num) {
    global $base_url;
    $clip_file_size=0;
    if($node->field_file_size->first()){
      $clip_file_size=$node->field_file_size->first()->value;
    }
    $source_thumbnail='';
    $clip_id='';
    if($node->field_videoid->first()) {
      $source_thumbnail =
        CommonHelper::$s3url 
        . $node->field_videoid->first()->value . "/thumbnails/default.jpg";
      $clip_id=$node->field_videoid->first()->value;
    }
    $title_clip=$node->getName();
    $note_clip = '';
    if($node->field_note->first()) {
      $note_clip= substr($node->field_note->first()->value, 0, 200);
    }
    $exact_file_size= dd_clip_format_size_units($clip_file_size);
    $video_tags = CommonHelper::getClipTags(array($node));

    $hearing_path = '/hearings';
    if ($node->field_hearing_url->first()
        && $node->field_hearing_url->first()->value) {
      $hearing_url = explode('/', $node->field_hearing_url->first()->value);
      $hearing_path = '/hearing/' . $hearing_url[0] . '?vid=' . $hearing_url[1];
    }

    $cut_url = Url::fromRoute('dd_video_editor.video_clipper', [
      'user' => \Drupal::currentUser()->id(),
      'arg1' => 'clip=' . $clip_id,
    ])->toString();

    $options = ['absolute' => TRUE, 'query' => ['contentonly' => 'true']];
    $embed_url = Url::fromRoute('dd_clip.clip_view', 
      ['videoid' => $clip_id], $options)->toString();

    $edit_url = Url::fromRoute('dd_clip.clip_edit',
      ['videoid' => $clip_id])->toString();
    $delete_url = Url::fromRoute('dd_clip.clip_delete',
      ['videoid' => $clip_id])->toString();

    $anno_url = Url::fromRoute('dd_video_editor.video_annotator', [
      'user' => \Drupal::currentUser()->id(),
      'clip_id' => $clip_id,
    ])->toString();

    $item = array(
      'source_thumbnail' => $source_thumbnail,
      'clip_id' => $clip_id,
      'title_clip' => $title_clip,
      'note_clip' => $note_clip,
      'clip_length' =>
        CommonHelper::formatSeconds($node->field_duration_float->first()->value),
      'exact_file_size' => $exact_file_size,
      'delete_path' => $delete_url,
      'node_status' => $node->isPublished(),
      'nid' => $node->id(),
      'tags' => implode(' ', $video_tags),
      'hearing_path' => $hearing_path,
      'row_num' => $row_num,
      'iframe_width' => \Drupal::config('dd_video_editor.settings')
                          ->get('clip.iframe_width'),
      'iframe_height' => \Drupal::config('dd_video_editor.settings')
                          ->get('clip.iframe_height'),
      'url' => $base_url . '/clip/' . $clip_id,
      'cut_url' => $cut_url,
      'embed_url' => $embed_url,
      'edit_url' => $edit_url,
      'delete_url' => $delete_url,
      'anno_url' => $anno_url,
      'anno_perm' => CommonHelper::userCanAnnotateClip($clip_id),
    );
    return $item;
  }
}

