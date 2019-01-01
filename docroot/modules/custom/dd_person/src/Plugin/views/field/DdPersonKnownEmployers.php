<?php

namespace Drupal\dd_person\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\dd_admin\DdAdmin;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_person_known_employers")
 */
class DdPersonKnownEmployers extends PrerenderList {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');
    $options['show_all'] = array('default' => FALSE);
    $options['current_year'] = array('default' => FALSE);
    $options['year'] = array('default' => '');

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
    $form['show_all'] = [
      '#title' => $this->t('Show All'),
      '#description' => $this->t('Show all regardless of year'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['show_all'],
    ];
    $form['current_year'] = [
      '#title' => $this->t('Use Current Year'),
      '#description' => $this->t('Show for current year instead of year parameter'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['current_year'],
    ];
    $form['year'] = [
      '#title' => $this->t('Year'),
      '#description' => $this->t('Year to query, such as 2016, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['year'],
    ];
  }

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * @inheritDoc
   */
  public function getItems(ResultRow $values) {
    $index = 0;
    $items = array();

    // Hide data if lobbyists filter is set in admin.
    if (!DdAdmin::checkVisibility('visibility_lobbyists')) {
      return $items;
    }

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
      if ($this->options['show_all']) {
        $year = FALSE;
      }
      else {
        if ($this->options['current_year']) {
          $year = date('Y');
        }
        else {
          $year = $this->tokenizeValue($this->options['year'], $values->index);
        }
      }
      $clients = DdPerson::getKnownEmployersForPid($pid, $year);
      if ($clients) {
        foreach ($clients as $client) {
          if (isset($client->cid)) {
            $url = Link::createFromRoute($client->name, 'entity.dd_committee.canonical', array('dd_committee' => $client->cid));
            $text = $url->toRenderable();
          }
          else {
            $text = $client->name;
          }
          $items[$index++]['raw'] = $text;
        }
      }
    }

    return $items;
  }

  /**
   * @inheritDoc
   */
  public function render_item($count, $item) {
    return render($item['raw']);
  }
}
