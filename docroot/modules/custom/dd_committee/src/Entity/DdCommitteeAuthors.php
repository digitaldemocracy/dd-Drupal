<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Committee Authors entity.
 *
 * @ingroup dd_committee
 *
 * @ContentEntityType(
 *   id = "dd_committee_authors",
 *   label = @Translation("DD Committee Authors"),
 *   handlers = {
 *     "storage" = "Drupal\dd_committee\Entity\Sql\DdCommitteeSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_committee\DdCommitteeAuthorsListBuilder",
 *     "views_data" = "Drupal\dd_committee\Entity\DdCommitteeAuthorsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_committee\Form\DdCommitteeAuthorsForm",
 *       "edit" = "Drupal\dd_committee\Form\DdCommitteeAuthorsForm",
 *     },
 *     "access" = "Drupal\dd_committee\DdCommitteeAuthorsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_committee\DdCommitteeAuthorsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "CommitteeAuthors",
 *   admin_permission = "administer dd committee authors entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_committee_authors/{dd_committee_authors}",
 *     "edit-form" = "/admin/structure/dd_committee_authors/{dd_committee_authors}/edit",
 *     "collection" = "/admin/structure/dd_committee_authors",
 *   },
 *   field_ui_base_route = "dd_committee_authors.settings"
 * )
 */
class DdCommitteeAuthors extends DdBaseContentEntity implements DdCommitteeAuthorsInterface {
  /**
   * Get Authors Drupal IDs for a committee ID.
   *
   * @param int $cid
   *   Committee ID.
   *
   * @return array
   *   Array of Drupal IDs.
   */
  public static function getAuthorsForCommittee($cid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CommitteeAuthors', 'ca');
    $query->fields('ca', ['dr_id']);
    $query->condition('ca.cid', $cid);
    return $query->execute()->fetchCol();
  }

  /**
   * Get Committee Authors for a bill.
   *
   * @param string $bid
   *   Bill ID.
   * @param string $vid
   *   Bill Version ID, optional otherwise return all version's committees.
   *
   * @return array
   *   Array of committee IDs.
   */
  public static function getCommitteeAuthorsForBill($bid, $vid = '') {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CommitteeAuthors', 'ca');
    $query->fields('ca', ['cid']);
    $query->condition('ca.bid', $bid);

    if ($vid != '') {
      $query->condition('ca.vid', $vid);
    }
    return $query->execute()->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function getCid() {
    return $this->get('cid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCid($cid) {
    $this->set('cid', $cid);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getBid() {
    return $this->get('bid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBid($bid) {
    $this->set('bid', $bid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVid() {
    return $this->get('vid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVid($vid) {
    $this->set('vid', $vid);
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

    $fields['cid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee ID'))
      ->setSetting('target_type', 'dd_committee')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['vid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill Version ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
