<?php

namespace Drupal\dd_base\Plugin\search_api\processor;

use Drupal\search_api\Processor\ProcessorPluginBase;

/**
 * Excludes unpublished entities from entity indexes.
 *
 * @SearchApiProcessor(
 *   id = "entity_status",
 *   label = @Translation("Entity status"),
 *   description = @Translation("Exclude unpublished entities from indexes."),
 *   stages = {
 *     "alter_items" = 0,
 *   },
 * )
 */
class EntityStatus extends ProcessorPluginBase {
  /**
   * {@inheritdoc}
   */
  public function alterIndexedItems(array &$items) {
    // Annoyingly, this doc comment is needed for PHPStorm. See
    // http://youtrack.jetbrains.com/issue/WI-23586
    /** @var \Drupal\search_api\Item\ItemInterface $item */
    foreach ($items as $item_id => $item) {
      $object = $item->getOriginalObject()->getValue();
      if (method_exists($object, 'isPublished') && !$object->isPublished()) {
        unset($items[$item_id]);
      }
    }
  }
}
