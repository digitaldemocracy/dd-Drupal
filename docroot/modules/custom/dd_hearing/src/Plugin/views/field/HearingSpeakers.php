<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\HearingSpeakers
 */

namespace Drupal\dd_hearing\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide hearing speakers.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("hearing_speakers")
 */
class HearingSpeakers extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
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
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $output = '';
    $results = DdHearing::getSpeakersForHearing($values->hid, FALSE);
    if ($results) {
      foreach ($results as $result) {
        $item = array(
          '#type' => 'container',
          '#attributes' => array('class' => array('person', 'views-row')),
          '#children' => array(
            '#type' => 'markup',
            '#markup' => '<a class="speaker-utterance-link" data-youtubeid="' . $result->fileId . '" data-starttime="' . $result->time . '">' . $result->last . ', ' . $result->first . '</a>',
          ),
        );
        $output .= $this->getRenderer()->render($item);
      }
    }
    return ViewsRenderPipelineMarkup::create($output);
  }
}
