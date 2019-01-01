<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Form\VideoTagsEditForm.
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
    
/**
 * Form for editing clip tags. Accessible on my clip bank block.
 */
class VideoTagsEditForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_editor_video_tags_edit_form';
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

    $form['hidden']['clips'] = array(
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 2028,
      '#default_value' => '',
    );

    $form['hidden']['submit'] = array('#type' => 'submit');

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
    $form_data = $form_state->getValues();
    $user = \Drupal::currentUser();
    $clips = json_decode($form_data['clips'], true);
    // For each clip
    foreach ($clips as $id => $clip) {
      $node = DdClip::load($id);
      $new_tags = array();
      // For each tag
      foreach($clip['tags'] as $tag) {
        // Get taxonomy term
        $term = CommonHelper::getOrCreateTagTerm($tag, 'video_tags');
        $new_tags[] = array('target_id' => $term->id());
      }
      // Save changes to node
      $node->field_video_tags->setValue($new_tags);
      $node->save();
    }
  }

}
