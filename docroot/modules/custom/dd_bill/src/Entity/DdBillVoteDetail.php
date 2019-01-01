<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;

/**
 * Defines the DD BillVoteDetail entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill_vote_detail",
 *   label = @Translation("DD BillVoteDetail"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_bill\DdBillAccessControlHandler",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillVoteDetailViewsData",
 *   },
 *   base_table = "BillVoteDetail",
 *   admin_permission = "administer dd bill entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "voteId",
 *   },
 * )
 */
class DdBillVoteDetail extends DdBaseStateField implements DdBillVoteDetailInterface {
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
   * @inheritDoc
   */
  public function getVoteId() {
    return $this->get('voteId')->value;
  }

  /**
   * @inheritDoc
   */
  public function setVoteId($vote_id) {
    $this->set('voteId', $vote_id);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getResult() {
    return $this->get('result')->value;
  }

  /**
   * @inheritDoc
   */
  public function setResult($result) {
    $this->set('result', $result);
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
      ->setSetting('target_type', 'dd_person')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['voteId'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vote ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['result'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Result'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
