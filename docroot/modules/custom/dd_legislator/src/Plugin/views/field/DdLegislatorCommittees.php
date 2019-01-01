<?php

namespace Drupal\dd_legislator\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_committee\Entity\DdServesOn;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;

/**
 * Field handler for DdLegislator Committees.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_legislator_committees")
 */
class DdLegislatorCommittees extends PrerenderList {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = ['default' => ''];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['pid'] = [
      '#title' => $this->t('Person ID'),
      '#description' => $this->t('PID such as 22, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['pid'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  public function getItems(ResultRow $values) {
    $items = [];
    $index = 0;

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);

      $serves_on_ids = DdServesOn::getServesOnIdsForPid($pid, TRUE);
      if (!empty($serves_on_ids)) {
        $serves_on_entities = DdServesOn::loadMultiple($serves_on_ids);
        foreach ($serves_on_entities as $serves_on_entity) {
          $committee = DdCommittee::load($serves_on_entity->getCid()[0]['target_id']);
          if ($committee->getType() != 'Floor') {
            $url = Url::fromUserInput('/committee/' . $committee->getCommitteeNameId());
            $items[$index]['raw'] = [
              '#type' => 'link',
              '#url' => $url,
              '#title' => $committee->getName(),
              '#attributes' => ['target' => '_blank'],
            ];
            $index++;
          }
        }
      }
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function render_item($count, $item) {
    return render($item['raw']);
  }
}
