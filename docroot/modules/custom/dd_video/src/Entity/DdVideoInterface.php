<?php

namespace Drupal\dd_video\Entity;

use Drupal\dd_base\Entity\DdBaseStateFieldInterface;

/**
 * Provides an interface for defining DD Video entities.
 *
 * @ingroup dd_video
 */
interface DdVideoInterface extends DdBaseStateFieldInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD Video fileId.
   *
   * @return string
   *   FileId of the DD Video.
   */
  public function getFileId();

  /**
   * Sets the DD Video fileId.
   *
   * @param string $file_id
   *   The DD Video fileId.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setFileId($file_id);

  /**
   * Gets the DD Video hid.
   *
   * @return int
   *   Hid of the DD Video.
   */
  public function getHid();

  /**
   * Sets the DD Video hid.
   *
   * @param int $hid
   *   The DD Video hid.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD Video position.
   *
   * @return int
   *   Position of the DD Video.
   */
  public function getPosition();

  /**
   * Sets the DD Video position.
   *
   * @param int $position
   *   The DD Video position.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setPosition($position);
  /**
   * Gets the DD Video startOffset.
   *
   * @return int
   *   StartOffset of the DD Video.
   */
  public function getStartOffset();

  /**
   * Sets the DD Video startOffset.
   *
   * @param int $start_offset
   *   The DD Video startOffset.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setStartOffset($start_offset);

  /**
   * Gets the DD Video duration.
   *
   * @return int
   *   Duration of the DD Video.
   */
  public function getDuration();

  /**
   * Sets the DD Video duration.
   *
   * @param int $duration
   *   The DD Video duration.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setDuration($duration);
  /**
   * Gets the DD Video srtFlag.
   *
   * @return int
   *   SrtFlag of the DD Video.
   */
  public function getSrtFlag();

  /**
   * Sets the DD Video srtFlag.
   *
   * @param int $srt_flag
   *   The DD Video srtFlag.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setSrtFlag($srt_flag);

  /**
   * Gets the DD Video source.
   *
   * @return int
   *   Source of the DD Video.
   */
  public function getSource();

  /**
   * Sets the DD Video source.
   *
   * @param int $source
   *   The DD Video source.
   *
   * @return \Drupal\dd_video\Entity\DdVideoInterface
   *   The called DD Video entity.
   */
  public function setSource($source);
}
