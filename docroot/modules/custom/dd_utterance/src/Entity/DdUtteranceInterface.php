<?php

namespace Drupal\dd_utterance\Entity;

use Drupal\dd_person\Entity\DdPersonContentEntityInterface;

/**
 * Provides an interface for defining DD Utterance entities.
 *
 * @ingroup dd_utterance
 */
interface DdUtteranceInterface extends DdPersonContentEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Utterance vid.
   *
   * @return string
   *   Vid of the DD Utterance.
   */
  public function getVid();

  /**
   * Sets the DD Utterance vid.
   *
   * @param string $vid
   *   The DD Utterance vid.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setVid($vid);


  /**
   * Gets the DD Utterance pid.
   *
   * @return string
   *   Pid of the DD Utterance.
   */
  public function getPid();

  /**
   * Sets the DD Utterance pid.
   *
   * @param string $pid
   *   The DD Utterance pid.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setPid($pid);

  /**
   * Gets the DD Utterance time.
   *
   * @return string
   *   Time of the DD Utterance.
   */
  public function getTime();

  /**
   * Sets the DD Utterance time.
   *
   * @param string $time
   *   The DD Utterance time.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setTime($time);

  /**
   * Gets the DD Utterance endTime.
   *
   * @return string
   *   EndTime of the DD Utterance.
   */
  public function getEndTime();

  /**
   * Sets the DD Utterance endTime.
   *
   * @param string $end_time
   *   The DD Utterance endTime.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setEndTime($end_time);

  /**
   * Gets the DD Utterance text.
   *
   * @return string
   *   Text of the DD Utterance.
   */
  public function getText();

  /**
   * Sets the DD Utterance text.
   *
   * @param string $text
   *   The DD Utterance text.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setText($text);

  /**
   * Gets the DD Utterance type.
   *
   * @return string
   *   Type of the DD Utterance.
   */
  public function getType();

  /**
   * Sets the DD Utterance type.
   *
   * @param string $type
   *   The DD Utterance type.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setType($type);

  /**
   * Gets the DD Utterance alignment.
   *
   * @return string
   *   Alignment of the DD Utterance.
   */
  public function getAlignment();

  /**
   * Sets the DD Utterance alignment.
   *
   * @param string $alignment
   *   The DD Utterance alignment.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setAlignment($alignment);

  /**
   * Gets the DD Utterance did.
   *
   * @return string
   *   Did of the DD Utterance.
   */
  public function getDid();

  /**
   * Sets the DD Utterance did.
   *
   * @param string $did
   *   The DD Utterance did.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setDid($did);

}
