<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Speaker Participation entities.
 *
 * @ingroup dd_person
 */
interface DdSpeakerParticipationInterface extends DdBaseStateFieldInterface {

  /**
   * Gets the DD Speaker Participation pid.
   *
   * @return string
   *   Pid of the DD Speaker Participation.
   */
  public function getPid();

  /**
   * Sets the DD Speaker Participation pid.
   *
   * @param string $pid
   *   The DD Speaker Participation pid.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Speaker Participation session year.
   *
   * @return int
   *   Session year of the DD Speaker Participation.
   */
  public function getSessionYear();

  /**
   * Sets the DD Speaker Participation session year.
   *
   * @param int $session_year
   *   The DD Speaker Participation session year.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setSessionYear($session_year);

  /**
   * Gets the DD Speaker Participation word count total.
   *
   * @return int
   *   Total word count of the DD Speaker Participation.
   */
  public function getWordCountTotal();

  /**
   * Sets the DD Speaker Participation word count total.
   *
   * @param int $word_count_total
   *   The DD Speaker Participation word count total.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setWordCountTotal($word_count_total);

  /**
   * Gets the DD Speaker Participation word count hearing avg.
   *
   * @return float
   *   Average hearing word count of the DD Speaker Participation.
   */
  public function getWordCountHearingAvg();

  /**
   * Sets the DD Speaker Participation word count hearing avg.
   *
   * @param float $word_count_hearing_avg
   *   The DD Speaker Participation word count hearing avg.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setWordCountHearingAvg($word_count_hearing_avg);

  /**
   * Gets the DD Speaker Participation word count hearing avg.
   *
   * @return int
   *   Total time of the DD Speaker Participation.
   */
  public function getTimeTotal();

  /**
   * Sets the DD Speaker Participation total time.
   *
   * @param int $time_total
   *   The DD Speaker Participation total time.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setTimeTotal($time_total);

  /**
   * Gets the DD Speaker Participation time hearing avg.
   *
   * @return float
   *   Average hearing time of the DD Speaker Participation.
   */
  public function getTimeHearingAvg();

  /**
   * Sets the DD Speaker Participation time hearing avg.
   *
   * @param float $time_hearing_avg
   *   The DD Speaker Participation time hearing avg.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setTimeHearingAvg($time_hearing_avg);

  /**
   * Gets the DD Speaker Participation bill discussion count.
   *
   * @return int
   *   Number of bill discussions of the DD Speaker Participation.
   */
  public function getBillDiscussionCount();

  /**
   * Sets the DD Speaker Participation bill discussion count.
   *
   * @param int $bill_discussion_count
   *   The DD Speaker Participation bill discussion count.
   *
   * @return \Drupal\dd_person\Entity\DdSpeakerParticipationInterface
   *   The called DD Speaker Participation entity.
   */
  public function setBillDiscussionCount($bill_discussion_count);
}
