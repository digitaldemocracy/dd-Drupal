<?php
/**
 * @file
 * Definition of Drupal\dd_hearing\Plugin\views\field\BillDiscussionSpeakers
 */

namespace Drupal\dd_bill\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_bill\Entity\DdBillDiscussion;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide Bill Discussion Speakers.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("bill_discussion_speakers")
 */
class BillDiscussionSpeakers extends FieldPluginBase {

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
    $results = DdBillDiscussion::getSpeakersForBillDiscussion($values->billdiscussion_hearing_did, FALSE);
    $index = 1;
    foreach ($results as $result) {
      $item = array(
        '#type' => 'container',
        '#attributes' => array('class' => 'views-row'),
        '#children' => array(
          '#type' => 'link',
          '#title' => $result->first . ' ' . $result->last . (($index != count($results)) ? ',' : ''),
          '#url' => Url::fromUri('internal:/hearing/' . $result->hid, array('query' => array('startTime' => $result->time, 'vid' => $result->fileId))),
          '#prefix' => '<span class="billdiscussion-speaker">',
          '#suffix' => '</span>',
        ),
      );
      $output .= $this->getRenderer()->render($item);
      $index++;
    }
    return ViewsRenderPipelineMarkup::create($output);
  }
}
