<?php

namespace Drupal\dd_committee\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 * A handler to provide committee name id from search index.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_committee_name_id")
 */
class CommitteeNameId extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $object = $values->_item->getOriginalObject()->getValue();
    return $object->getCommitteeNameId();
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // This function exists to override parent query function.
    // Do nothing.
  }
}
