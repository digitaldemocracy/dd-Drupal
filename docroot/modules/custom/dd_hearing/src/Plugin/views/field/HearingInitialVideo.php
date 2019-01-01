<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\HearingInitialVideo
 */

namespace Drupal\dd_hearing\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Field handler to provide hearing initial video.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("hearing_initial_video")
 */
class HearingInitialVideo extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options.
   * @return array
   *   Array of options.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $output = '';
    $entity_obj = $values->_item->getOriginalObject();
    $hid = $entity_obj->get('hid')->value;
    if ($hid) {
      $result = DdHearing::getInitialHearingVideo($hid);
      if ($result) {
        $url = Url::fromUserInput('/hearing/' . $hid, array(
          'query' => array(
            'startTime' => $result->startOffset,
            'vid' => $result->fileId,
          ),
        ));
        $link = Link::fromTextAndUrl('', $url)->toRenderable();
        $item = array(
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#attributes' => array('class' => array('hearing--play-icon')),
          '#value' => $this->getRenderer()->render($link),
        );

        $output .= $this->getRenderer()->render($item);
      }
      return ViewsRenderPipelineMarkup::create($output);
    }
  }
}
