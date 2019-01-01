<?php

namespace Drupal\dd_person\Plugin\views\field;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\dd_legislator\Entity\DdTerm;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;
use Drupal\dd_person\Entity\DdPerson;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_person_classifications")
 */
class DdPersonClassifications extends PrerenderList {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['pid'] = array('default' => '');
    $options['show_current_former'] = array('default' => -1);
    $options['limit_classifications'] = array('default' => FALSE);
    $options['show_house'] = array('default' => TRUE);
    $options['show_former_years'] = array('default' => TRUE);
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
    $form['show_current_former'] = [
      '#title' => $this->t('Show Former Classifications'),
      '#description' => $this->t('Show Former Classifications if checked, Current otherwise'),
      '#type' => 'radios',
      '#options' => array(
        -1 => t('Show All'),
        1 => t('Show Current'),
        0 => t('Show Former'),
      ),
      '#default_value' => $this->options['show_current_former'],
    ];
    $form['limit_classifications'] = [
      '#title' => $this->t('Limit to 1 Classification'),
      '#description' => $this->t('(Requires Is Current selected) Will only show 1 classification from a pre-determined order.'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['limit_classifications'],
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
    $form['show_house'] = [
      '#title' => $this->t('Show House on Legislators'),
      '#description' => $this->t('Shows "Name | Assembly" for example on legislators'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['show_house'],
    ];
    $form['show_former_years'] = [
      '#title' => $this->t('Show Years next to classification on former'),
      '#description' => $this->t('Shows "Lobbyist (2014, 2015)" for example on classification'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['show_former_years'],
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
    $index = 0;
    $items = array();
    $grouped_classifications = array();

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['pid'])) {
      $pid = $this->tokenizeValue($this->options['pid'], $values->index);
      if ($this->options['show_current_former'] == 1) {
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
      $classifications = DdPerson::getClassificationsForPid($pid, -1, $year);
      if ($classifications) {
        foreach ($classifications as $classification) {
          if (
            $this->options['show_current_former'] == -1 ||
            ($this->options['show_current_former'] != -1 && $this->options['show_current_former'] == $classification->is_current)
          ) {
            $grouped_classifications[$classification->PersonType]['classification'] = $classification->PersonType;

            if (strtolower($classification->PersonType) == 'legislator') {
              // Get more info for legislator.
              /** @var \Drupal\Core\Database\Query\Select $query */
              $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Term', 't');
              $query->condition('t.pid', $pid);
              $query->condition('current_term', '1');
              $query->fields('t', ['dr_id']);
              $ids = $query->execute()->fetchCol();

              if ($ids) {
                // this function takes dr_id as its argument, not pid!!!
                $term = DdTerm::load(reset($ids));
                if ($this->options['show_current_former'] == 1 && $term) {
                  $grouped_classifications[$classification->PersonType]['classification'] = $classification->PersonType . ' | ' . $term->getHouse();
                }
              }
            }

            if ($classification->specific_year) {
              $grouped_classifications[$classification->PersonType]['years'][$classification->specific_year] = $classification->specific_year;
            }
          }
        }

        // Limit Classifications based on predetermined ordering.
        $limit_order = [
          'Legislator' => 1,
          'Lobbyist' => 2,
          'Legislative Analyst Office' => 3,
          'State Agency Representative' => 4,
          'State Constitutional Office' => 5,
          'Legislative Staff' => 6,
          'General Public' => 7,
         ];

        if ($this->options['limit_classifications']) {
          foreach ($grouped_classifications as &$grouped_classification) {
            $weight = isset($limit_order[$grouped_classification['classification']]) ? $limit_order[$grouped_classification['classification']] : 0;
            $grouped_classification['weight'] = $weight;
          }

          usort($grouped_classifications,
            function ($item1, $item2) {
              if ($item1['weight'] == $item2['weight']) {
                return 0;
              }
            return $item1['weight'] < $item2['weight'] ? -1 : 1;
            }
          );

          $items[$index]['raw']['classification'] = array(
            '#type' => '#markup',
            '#markup' => reset($grouped_classifications)['classification'],
          );

        }
        else {

          // Show years grouped in () for classifications.
          foreach ($grouped_classifications as $grouped_classification) {
            $items[$index]['raw']['classification'] = array(
              '#type' => '#markup',
              '#markup' => $grouped_classification['classification'],
            );

            if ($this->options['show_former_years'] && $this->options['show_current_former'] != 1 && isset($grouped_classification['years'])) {
              $grouped_year_index = 0;
              $items[$index]['raw']['years']['#prefix'] = ' (';
              $items[$index]['raw']['years']['#suffix'] = ')';
              ksort($grouped_classification['years']);
              foreach ($grouped_classification['years'] as $grouped_year) {
                $url = Url::fromUserInput('#person_top_block_years--block_former_classifications_block_group-' . $grouped_year);
                $link = Link::fromTextAndUrl($grouped_year, $url)
                  ->toRenderable();
                $items[$index]['raw']['years'][$grouped_year_index] = $link;
                $items[$index]['raw']['years'][$grouped_year_index]['#attributes'] = array('class' => array('view-showhide-open-past-affiliations'));

                if ($grouped_year_index < count($grouped_classification['years']) - 1) {
                  $items[$index]['raw']['years'][$grouped_year_index]['#suffix'] = ', ';
                }
                $grouped_year_index++;
              }
            }
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
