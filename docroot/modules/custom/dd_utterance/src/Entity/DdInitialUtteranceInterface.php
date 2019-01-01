<?php

namespace Drupal\dd_utterance\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD InitialUtterance entities.
 *
 * @ingroup dd_utterance
 */
interface DdInitialUtteranceInterface extends ContentEntityInterface  {
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
   * Gets the DD Utterance uid.
   *
   * @return string
   *   Uid of the DD Utterance.
   */
  public function getUid();

  /**
   * Sets the DD Utterance uid.
   *
   * @param string $uid
   *   The DD Utterance uid.
   *
   * @return \Drupal\dd_utterance\Entity\DdUtteranceInterface
   *   The called DD Utterance entity.
   */
  public function setUid($uid);

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
