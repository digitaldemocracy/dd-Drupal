<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD BillVoteSummary entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill_vote_summary",
 *   label = @Translation("DD BillVoteSummary"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\dd_bill\DdBillAccessControlHandler",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillVoteSummaryViewsData",
 *   },
 *   base_table = "BillVoteSummary",
 *   admin_permission = "administer dd bill entities",
 *   entity_keys = {
 *     "id" = "voteId",
 *     "label" = "bid",
 *   },
 * )
 */
class DdBillVoteSummary extends DdBaseContentEntity implements DdBillVoteSummaryInterface {
  /**
   * Determine if bill is passed.
   *
   * @param string $bid
   *   Bill ID
   * 
   * @param string $date
   *   Date in format August 1, 2016
   *
   * @returns bool
   *  TRUE if passed, false if failed, NULL if no votes.
   */
  public static function isBillPassed($bid, $date) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())
      ->select('BillVoteSummary', 'bvs');
    $query->join('Motion', 'm', 'm.mid = bvs.mid');
    $query->condition('bvs.bid', $bid);
    $query->where("DATE_FORMAT(bvs.VoteDate, '%Y-%m-%d') = STR_TO_DATE('" . $date . "', '%M %d, %Y')");
    $query->addExpression('IF(bvs.result=\'(PASS)\', 1, 0)', 'result');
    $query->fields('bvs', array('result'));
    $query->fields('m', array('doPass'));
    $query->range(0, 1);
    $query->orderBy('bvs.VoteDateSeq', 'DESC');
    $result = $query->execute()->fetchObject();

    if ($result) {
      // Logic from D7 dd_person_import.module.
      return !((int) $result->result xor (int) $result->doPass);
    }
    return NULL;
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
  public function getMid() {
    return $this->get('mid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setMid($mid) {
    $this->set('mid', $mid);
    return $this;
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
  public function getVoteDate() {
    return $this->get('VoteDate_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVoteDate($vote_date) {
    $this->set('VoteDate_ts', $vote_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAyes() {
    return $this->get('ayes')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAyes($ayes) {
    $this->set('ayes', $ayes);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNaes() {
    return $this->get('naes')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNaes($naes) {
    $this->set('naes', $naes);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAbstain() {
    return $this->get('abstain')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAbstain($abstain) {
    $this->set('abstain', $abstain);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getResult() {
    return $this->get('result')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setResult($result) {
    $this->set('result', $result);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVoteDateSeq() {
    return $this->get('VoteDateSeq')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVoteDateSeq($vote_date_seq) {
    $this->set('VoteDateSeq', $vote_date_seq);
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
    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['mid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Motion'))
      ->setSetting('target_type', 'dd_motion')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee ID'))
      ->setSetting('target_type', 'dd_committee')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['VoteDate_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Vote Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['ayes'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Ayes'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['naes'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Naes'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['abstain'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Abstain'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['result'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Result'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['VoteDateSeq'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Vote Date Seq'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
