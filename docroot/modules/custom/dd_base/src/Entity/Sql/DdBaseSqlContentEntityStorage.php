<?php

namespace Drupal\dd_base\Entity\Sql;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;

/**
 * Class DdBaseSqlContentEntityStorage
 * @package Drupal\dd_base\Entity\Sql
 */
class DdBaseSqlContentEntityStorage extends SqlContentEntityStorage {

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeInterface $entity_type, Connection $database, EntityManagerInterface $entity_manager, CacheBackendInterface $cache, LanguageManagerInterface $language_manager) {
    // Override SQL storage to use dddb database.
    if (in_array($entity_type->getBaseTable(), DdEnvironment::getDddbTables())) {
      $database = Database::getConnection('default', DdBase::getDddbName());
    }
    parent::__construct($entity_type, $database, $entity_manager, $cache, $language_manager);
  }

  /**
   * Function to load entities by field values.
   *
   * @param array $fields_vals
   *   array of field names with field/value/op parameters.
   * @param array $order_bys
   *   array of field names with field/dir (ASC or DESC) params to sort result.
   *
   * @return array
   *   array of DdCurrentUtterance objects
   */
  public function loadByFields(array $fields_vals, array $order_bys = NULL) {
    $query = $this->getQuery();

    if ($fields_vals) {
      foreach ($fields_vals as $field) {
        if (!isset($field['op'])) {
          $query->condition($field['field'], $field['value']);
        }
        else {
          $query->condition($field['field'], $field['value'], $field['op']);
        }
      }
    }
    if ($order_bys) {
      foreach ($order_bys as $order_by) {
        $query->sort($order_by['field'], $order_by['dir']);
      }
    }
    $result = $query->execute();
    return $result ? self::loadMultiple($result) : array();
  }
}
