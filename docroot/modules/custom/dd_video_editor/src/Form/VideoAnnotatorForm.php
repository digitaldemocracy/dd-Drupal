<?php
/**
 * @file
 * Contains \Drupal\dd_video_editor\Form\VideoAnnotatorForm.
 */

namespace Drupal\dd_video_editor\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use \Drupal\dd_video_editor\Utility\CommonHelper;
use \Drupal\dd_clip\Entity\DdClip;
    
/**
 * Contribute form for annotating clip.
 */
class VideoAnnotatorForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_video_editor_video_annotator_form';
  }
    
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];

    $form['anno'] = array(
      '#type' => 'textfield',
      '#maxlength' => 2028,
      '#default_value' => '',
      '#prefix' => '<div class="hide">',
      '#suffix' => '</div>',
    );

    $form['nid'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['nid'],
    );

    $form['video_id'] = array(
      '#type' => 'hidden',
      '#value' => $build_args['video_id'],
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Save Changes',
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
    $form_data = $form_state->getValues();
    $node = DdClip::load($form_data['nid']);
    $node->field_annotations->setValue($form_data['anno']);
    $node->save();
  }
}
