<?php

namespace Drupal\dd_bill\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD BillVoteDetail entities.
 *
 * @ingroup dd_bill
 */
interface DdBillVoteDetailInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD BillVoteDetail pid.
   *
   * @return string
   *   Pid of the DD BillVoteDetail.
   */
  public function getPid();

  /**
   * Sets the DD BillVoteDetail pid.
   *
   * @param string $pid
   *   The DD BillVoteDetail pid.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteDetail entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD BillVoteDetail voteId.
   *
   * @return string
   *   VoteId of the DD BillVoteDetail.
   */
  public function getVoteId();

  /**
   * Sets the DD BillVoteDetail voteId.
   *
   * @param string $vote_id
   *   The DD BillVoteDetail voteId.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteDetail entity.
   */
  public function setVoteId($vote_id);

  /**
   * Gets the DD BillVoteDetail result.
   *
   * @return string
   *   Result of the DD BillVoteDetail.
   */
  public function getResult();

  /**
   * Sets the DD BillVoteDetail result.
   *
   * @param string $result
   *   The DD BillVoteDetail result.
   *
   * @return \Drupal\dd_bill\Entity\DdBillInterface
   *   The called DD BillVoteDetail entity.
   */
  public function setResult($result);

}
