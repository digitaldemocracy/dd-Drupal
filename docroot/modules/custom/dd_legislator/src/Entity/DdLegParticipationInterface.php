<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD LegParticipation entities.
 *
 * @ingroup dd_legislator
 */
interface DdLegParticipationInterface extends ContentEntityInterface  {

  // Add get/set methods for your configuration properties here.
  /**
   * Gets the DD LegParticipation hid.
   *
   * @return string
   *   Hid of the DD LegParticipation.
   */
  public function getHid();

  /**
   * Sets the DD LegParticipation hid.
   *
   * @param string $hid
   *   The DD LegParticipation hid.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD LegParticipation did.
   *
   * @return string
   *   Did of the DD LegParticipation.
   */
  public function getDid();

  /**
   * Sets the DD LegParticipation did.
   *
   * @param string $did
   *   The DD LegParticipation did.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setDid($did);

  /**
   * Gets the DD LegParticipation bid.
   *
   * @return string
   *   Bid of the DD LegParticipation.
   */
  public function getBid();

  /**
   * Sets the DD LegParticipation bid.
   *
   * @param string $bid
   *   The DD LegParticipation bid.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD LegParticipation pid.
   *
   * @return string
   *   Pid of the DD LegParticipation.
   */
  public function getPid();

  /**
   * Sets the DD LegParticipation pid.
   *
   * @param string $pid
   *   The DD LegParticipation pid.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD LegParticipation party.
   *
   * @return string
   *   Party of the DD LegParticipation.
   */
  public function getParty();

  /**
   * Sets the DD LegParticipation party.
   *
   * @param string $party
   *   The DD LegParticipation party.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setParty($party);

  /**
   * Gets the DD LegParticipation legBillWordCount.
   *
   * @return string
   *   LegBillWordCount of the DD LegParticipation.
   */
  public function getLegBillWordCount();

  /**
   * Sets the DD LegParticipation legBillWordCount.
   *
   * @param string $leg_bill_word_count
   *   The DD LegParticipation legBillWordCount.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegBillWordCount($leg_bill_word_count);

  /**
   * Gets the DD LegParticipation legBillTime.
   *
   * @return string
   *   LegBillTime of the DD LegParticipation.
   */
  public function getLegBillTime();

  /**
   * Sets the DD LegParticipation legBillTime.
   *
   * @param string $leg_bill_time
   *   The DD LegParticipation legBillTime.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegBillTime($leg_bill_time);

  /**
   * Gets the DD LegParticipation legBillPercentWord.
   *
   * @return string
   *   LegBillPercentWord of the DD LegParticipation.
   */
  public function getLegBillPercentWord();

  /**
   * Sets the DD LegParticipation legBillPercentWord.
   *
   * @param string $leg_bill_percent_word
   *   The DD LegParticipation legBillPercentWord.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegBillPercentWord($leg_bill_percent_word);

  /**
   * Gets the DD LegParticipation legBillPercentTime.
   *
   * @return string
   *   LegBillPercentTime of the DD LegParticipation.
   */
  public function getLegBillPercentTime();

  /**
   * Sets the DD LegParticipation legBillPercentTime.
   *
   * @param string $leg_bill_percent_time
   *   The DD LegParticipation legBillPercentTime.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegBillPercentTime($leg_bill_percent_time);

  /**
   * Gets the DD LegParticipation legHearingWordCount.
   *
   * @return string
   *   LegHearingWordCount of the DD LegParticipation.
   */
  public function getLegHearingWordCount();

  /**
   * Sets the DD LegParticipation legHearingWordCount.
   *
   * @param string $leg_hearing_word_count
   *   The DD LegParticipation legHearingWordCount.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegHearingWordCount($leg_hearing_word_count);

  /**
   * Gets the DD LegParticipation legHearingTime.
   *
   * @return string
   *   LegHearingTime of the DD LegParticipation.
   */
  public function getLegHearingTime();

  /**
   * Sets the DD LegParticipation legHearingTime.
   *
   * @param string $leg_hearing_time
   *   The DD LegParticipation legHearingTime.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegHearingTime($leg_hearing_time);


  /**
   * Gets the DD LegParticipation legHearingPercentWord.
   *
   * @return string
   *   LegHearingPercentWord of the DD LegParticipation.
   */
  public function getLegHearingPercentWord();

  /**
   * Sets the DD LegParticipation legHearingPercentWord.
   *
   * @param string $leg_hearing_percent_word
   *   The DD LegParticipation legHearingPercentWord.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegHearingPercentWord($leg_hearing_percent_word);

  /**
   * Gets the DD LegParticipation legHearingPercentTime.
   *
   * @return string
   *   LegHearingPercentTime of the DD LegParticipation.
   */
  public function getLegHearingPercentTime();

  /**
   * Sets the DD LegParticipation legHearingPercentTime.
   *
   * @param string $leg_hearing_percent_time
   *   The DD LegParticipation legHearingPercentTime.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegHearingPercentTime($leg_hearing_percent_time);

  /**
   * Gets the DD LegParticipation legHearingAvg.
   *
   * @return string
   *   LegHearingAvg of the DD LegParticipation.
   */
  public function getLegHearingAvg();

  /**
   * Sets the DD LegParticipation legHearingAvg.
   *
   * @param string $leg_hearing_avg
   *   The DD LegParticipation legHearingAvg.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setLegHearingAvg($leg_hearing_avg);

  /**
   * Gets the DD LegParticipation billWordCount.
   *
   * @return string
   *   BillWordCount of the DD LegParticipation.
   */
  public function getBillWordCount();

  /**
   * Sets the DD LegParticipation billWordCount.
   *
   * @param string $bill_word_count
   *   The DD LegParticipation billWordCount.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setBillWordCount($bill_word_count);

  /**
   * Gets the DD LegParticipation hearingWordCount.
   *
   * @return string
   *   HearingWordCount of the DD LegParticipation.
   */
  public function getHearingWordCount();

  /**
   * Sets the DD LegParticipation hearingWordCount.
   *
   * @param string $hearing_word_count
   *   The DD LegParticipation hearingWordCount.
   *
   * @return \Drupal\dd_legislator\Entity\DdLegParticipationInterface
   *   The called DD LegParticipation entity.
   */
  public function setHearingWordCount($hearing_word_count);
  
}
