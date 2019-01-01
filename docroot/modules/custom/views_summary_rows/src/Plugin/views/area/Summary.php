<?php

namespace Drupal\views_summary_rows\Plugin\views\area;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\area\TokenizeAreaPluginBase;

/**
 * Views area handler to display summary rows.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("summary_row")
 */
class Summary extends TokenizeAreaPluginBase {

  protected $numericalTypes = [
    'number_decimal',
    'number_integer',
  ];

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['content'] = array(
      'default' => '',
    );

    $options['require_exposed_filter_data'] = array(
      'default' => FALSE,
    );
    $options['exclude_exposed_filters'] = array(
      'default' => '',
    );

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    // Render summary tokens by field.
    foreach ($this->view->display_handler->getHandlers('field') as $field => $handler) {
      // Search for supported sum operation field types.
      if (in_array($handler->options['type'], $this->numericalTypes)) {
        $form['tokens']['tokens']['#items'][] = '{{ sum.' . $field . ' }} == Sum of ' . $handler->adminLabel();
        $form['tokens']['tokens']['#items'][] = '{{ min.' . $field . ' }} == Min of ' . $handler->adminLabel();
        $form['tokens']['tokens']['#items'][] = '{{ max.' . $field . ' }} == Max of ' . $handler->adminLabel();
      }
    }

    $form['content'] = array(
      '#title' => $this->t('Display'),
      '#type' => 'textarea',
      '#rows' => 3,
      '#default_value' => $this->options['content'],
      '#description' => $this->t('You may use HTML code in this field. Tokens are supported.'),
    );

    $form['require_exposed_filter_data'] = array(
      '#title' => $this->t('Require Exposed Filter data'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['require_exposed_filter_data'],
      '#description' => $this->t('If TRUE, at least one exposed filter needs to be populated for data to show.'),
    );
    $form['exclude_exposed_filters'] = array(
      '#title' => $this->t('Exclude Exposed Filters From Required'),
      '#type' => 'textfield',
      '#default_value' => $this->options['exclude_exposed_filters'],
      '#description' => $this->t('Enter a comma delimited list of exposed filter machine names to exclude from required.'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function tokenizeValue($value) {
    $tokens = [];
    foreach ($this->view->display_handler->getHandlers('field') as $field => $handler) {
      // Search for supported sum/avg/min/max operation field types.
      if (isset($handler->options['type']) && in_array($handler->options['type'], $this->numericalTypes)) {
        $result = $this->queryAggregate($field, $handler);
        if ($result) {
          $tokens['{{ sum.' . $field . ' }}'] = $result->sum_field ? $result->sum_field : '0';
          $tokens['{{ avg.' . $field . ' }}'] = $result->avg_field ? $result->avg_field : '0';
          $tokens['{{ min.' . $field . ' }}'] = $result->min_field ? $result->min_field : '0';
          $tokens['{{ max.' . $field . ' }}'] = $result->max_field ? $result->max_field : '0';
        }
      }
    }
    $value = str_replace(array_keys($tokens), array_values($tokens), $value);

    return parent::tokenizeValue($value);
  }

  /**
   * Query DB for fields aggregates.
   *
   * @param string $field
   *   Field name
   * @param object $handler
   *   Field handler
   *
   * @returns object
   *   Object of sum/avg/min/max aggregates for field.
   */
  public function queryAggregate($field, $handler) {
    // Make the query for the field.
    $view_query = $this->view->getQuery();
    $table = key(reset($view_query->tables));
    $query = \Drupal::database()->select($table);

    // Check where conditions.
    if ($this->query->where) {
      // Determine relationships to add.
      if (isset($view_query->tables[$table])) {
        foreach ($view_query->tables[$table] as $table_name => $table_count) {
          $table_info = $view_query->getTableInfo($table_name);
          if (isset($table_info['join'])) {
            $join = $table_info['join'];
            $join_type = ($join->type == 'LEFT') ? 'leftJoin' : 'join';
            $join_on = $join->leftTable . '.' . $join->leftField . ' = ' . $table_info['alias'] . '.' . $join->field;
            $query->$join_type($table_info['table'], $table_info['alias'], $join_on);
          }
        }
      }
      foreach ($this->query->where as $where_condition) {
        if ($where_condition['conditions']) {
          if ($where_condition['type'] == 'OR') {
            $query_group = $query->orConditionGroup();
          }
          else {
            $query_group = $query;
          }
          foreach ($where_condition['conditions'] as $where_condition_condition) {
            $query_group->condition($where_condition_condition['field'], $where_condition_condition['value'], $where_condition_condition['operator']);
          }
          if ($where_condition['type'] == 'OR') {
            $query->condition($query_group);
          }
        }
      }
    }

    $expression = (array_key_exists($field, $handler->aliases)) ?
      $handler->aliases[$field] :
      $handler->field;
    $query->addExpression('FORMAT(sum(' . $expression . '),2)', 'sum_field');
    $query->addExpression('FORMAT(avg(' . $expression . '),2)', 'avg_field');
    $query->addExpression('FORMAT(min(' . $expression . '),2)', 'min_field');
    $query->addExpression('FORMAT(max(' . $expression . '),2)', 'max_field');
    $results = $query->execute()->fetchObject();
    return $results;

  }

  /**
   * {@inheritdoc}
   */
  public function render($empty = FALSE) {
    // Return as render array.
    if ($this->showSummary()) {
      return array(
        '#type' => 'markup',
        '#markup' => $this->tokenizeValue($this->options['content']),
      );
    }
  }

  /**
   * Determine if summary should be shown.
   *
   * @return bool
   *   TRUE if should be shown, FALSE otherwise.
   */
  public function showSummary() {
    if ($this->options['require_exposed_filter_data']) {
      $exclude_exposed_filters = [];
      // Check that at least one filter is populated.
      if ($this->options['exclude_exposed_filters'] != '') {
        $exclude_exposed_filters = explode(',', $this->options['exclude_exposed_filters']);
      }
      foreach ($this->view->exposed_raw_input as $exposed_field => $exposed_value) {
        if ($exposed_value != '' && !in_array($exposed_field, $exclude_exposed_filters)) {
          return TRUE;
        }
      }
    }
    else {
      return TRUE;
    }
  }

}
