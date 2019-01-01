<?php

namespace Drupal\dd_base\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_base\DdBase;
use Drupal\views\Plugin\views\filter\StringFilter;

/**
 * Year filter to handle current year and session year.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("dd_year")
 */
class DdYearFilter extends StringFilter {
  /**
   * Add current year / session year as operators.
   */
  public function operators() {
    $operators = parent::operators();
    $operators += array(
      'current year' => array(
        'title' => $this->t('Is Current Year'),
        'method' => 'opYear',
        'short' => $this->t('current year'),
        'values' => 0,
      ),
      'session year' => array(
        'title' => $this->t('Is Current Session Year'),
        'method' => 'opYear',
        'short' => $this->t('current session year'),
        'values' => 0,
      ),
    );
    return $operators;
  }

  /**
   * Operator to filter on current/session year.
   *
   * @param string $field
   *   Field name
   */
  protected function opYear($field) {
    $year = date('Y');
    if ($this->operator == 'session year') {
      $year = DdBase::getSessionYear($year);
    }

    $this->query->addWhere($this->options['group'], $field, $year, '=');
  }
}
