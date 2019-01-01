<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Database\Driver\mysql\Select;
use Drupal\Core\Entity\Query\Sql\Query;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD Lobbyist entity.
 *
 * @ingroup dd_lobbyist
 *
 * @ContentEntityType(
 *   id = "dd_lobbyist",
 *   label = @Translation("DD Lobbyist"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbyist\DdLobbyistAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyistViewsData"
 *   },
 *   base_table = "Lobbyist",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "filer_id"
 *   },
 * )
 */
class DdLobbyist extends DdBaseStateField implements DdLobbyistInterface {

  /**
   * Get Known Clients for PID.
   *
   * @param int $pid
   *   Person ID.
   * @param int $year
   *   Year, or NULL to not filter.
   *
   * @return array
   *   Array of objects containing assoc_name, oid, year.
   */
  public static function getKnownClients($pid, $year = NULL) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('KnownClients', 'kc');
    /** @var Select $query */
    $query->condition('kc.pid', $pid);
    if ($year) {
      $query->condition('kc.year', $year);
    }
    $query->fields('kc', array('assoc_name', 'oid'));
    $query->addExpression('MAX(kc.year)', 'year');
    $query->groupBy('assoc_name');
    $query->groupBy('oid');
    $clients = $query->execute()->fetchAll();
    return $clients;
  }

  /**
   * {@inheritdoc}
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilerId() {
    return $this->get('filer_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFilerId($filer_id) {
    $this->set('filer_id', $filer_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['filer_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Filer ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
