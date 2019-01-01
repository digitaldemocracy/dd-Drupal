<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD BillVoteSummary entities.
 *
 * @ingroup dd_bill
 */
interface DdBillVoteSummaryInterface extends ContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD BillVoteSummary bid.
   *
   * @return string
   *   Bid of the DD BillVoteSummary.
   */
  public function getBid();

  /**
   * Sets the DD BillVoteSummary bid.
   *
   * @param string $bid
   *   The DD BillVoteSummary bid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD BillVoteSummary mid.
   *
   * @return string
   *   Mid of the DD BillVoteSummary.
   */
  public function getMid();

  /**
   * Sets the DD BillVoteSummary mid.
   *
   * @param string $mid
   *   The DD BillVoteSummary mid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setMid($mid);

  /**
   * Gets the DD BillVoteSummary cid.
   *
   * @return string
   *   Cid of the DD BillVoteSummary.
   */
  public function getCid();

  /**
   * Sets the DD BillVoteSummary cid.
   *
   * @param string $cid
   *   The DD BillVoteSummary cid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setCid($cid);

  /**
   * Gets the DD BillVoteSummary voteDate.
   *
   * @return string
   *   VoteDate of the DD BillVoteSummary.
   */
  public function getVoteDate();

  /**
   * Sets the DD BillVoteSummary voteDate.
   *
   * @param string $vote_date
   *   The DD BillVoteSummary voteDate.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setVoteDate($vote_date);

  /**
   * Gets the DD BillVoteSummary ayes.
   *
   * @return string
   *   Ayes of the DD BillVoteSummary.
   */
  public function getAyes();

  /**
   * Sets the DD BillVoteSummary ayes.
   *
   * @param string $ayes
   *   The DD BillVoteSummary ayes.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setAyes($ayes);
  /**
   * Gets the DD BillVoteSummary naes.
   *
   * @return string
   *   Naes of the DD BillVoteSummary.
   */
  public function getNaes();

  /**
   * Sets the DD BillVoteSummary naes.
   *
   * @param string $naes
   *   The DD BillVoteSummary naes.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setNaes($naes);

  /**
   * Gets the DD BillVoteSummary abstain.
   *
   * @return string
   *   Abstain of the DD BillVoteSummary.
   */
  public function getAbstain();

  /**
   * Sets the DD BillVoteSummary abstain.
   *
   * @param string $abstain
   *   The DD BillVoteSummary abstain.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setAbstain($abstain);

  /**
   * Gets the DD BillVoteSummary result.
   *
   * @return string
   *   Result of the DD BillVoteSummary.
   */
  public function getResult();

  /**
   * Sets the DD BillVoteSummary result.
   *
   * @param string $result
   *   The DD BillVoteSummary result.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setResult($result);

  /**
   * Gets the DD BillVoteSummary voteDateSeq.
   *
   * @return string
   *   VoteDateSeq of the DD BillVoteSummary.
   */
  public function getVoteDateSeq();

  /**
   * Sets the DD BillVoteSummary voteDateSeq.
   *
   * @param string $vote_date_seq
   *   The DD BillVoteSummary voteDateSeq.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteSummary entity.
   */
  public function setVoteDateSeq($vote_date_seq);
}
