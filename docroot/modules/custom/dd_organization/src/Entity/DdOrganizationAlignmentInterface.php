<?php

namespace Drupal\dd_organization\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD OrganizationAlignment entities.
 *
 * @ingroup dd_organization
 */
interface DdOrganizationAlignmentInterface extends ContentEntityInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the DD OrganizationAlignment oid.
   *
   * @return int
   *   Oid of the DD OrganizationAlignment.
   */
  public function getOid();

  /**
   * Sets the DD OrganizationAlignment oid.
   *
   * @param int $oid
   *   The DD Organization oid.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationAlignmentInterface
   *   The called DD OrganizationAlignment entity.
   */
  public function setOid($oid);

  /**
   * Gets the DD OrganizationAlignment bid.
   *
   * @return int
   *   Bid of the DD OrganizationAlignment.
   */
  public function getBid();

  /**
   * Sets the DD OrganizationAlignment bid.
   *
   * @param int $bid
   *   The DD OrganizationAlignment bid.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationAlignmentInterface
   *   The called DD OrganizationAlignment entity.
   */
  public function setBid($bid);

  /**
   * Gets the DD OrganizationAlignment hid.
   *
   * @return int
   *   Hid of the DD OrganizationAlignment.
   */
  public function getHid();

  /**
   * Sets the DD OrganizationAlignment hid.
   *
   * @param int $hid
   *   The DD OrganizationAlignment hid.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationAlignmentInterface
   *   The called DD OrganizationAlignment entity.
   */
  public function setHid($hid);

  /**
   * Gets the DD OrganizationAlignment alignment.
   *
   * @return string
   *   Alignment of the DD OrganizationAlignment.
   */
  public function getAlignment();

  /**
   * Sets the DD OrganizationAlignment alignment.
   *
   * @param string $alignment
   *   The DD OrganizationAlignment alignment.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationAlignmentInterface
   *   The called DD OrganizationAlignment entity.
   */
  public function setAlignment($alignment);

  /**
   * Gets the DD OrganizationAlignment analysisFlag.
   *
   * @return string
   *   AnalysisFlag of the DD OrganizationAlignment.
   */
  public function getAnalysisFlag();

  /**
   * Sets the DD OrganizationAlignment analysisFlag.
   *
   * @param string $analysis_flag
   *   The DD OrganizationAlignment analysisFlag.
   *
   * @return \Drupal\dd_organization\Entity\DdOrganizationAlignmentInterface
   *   The called DD OrganizationAlignment entity.
   */
  public function setAnalysisFlag($analysis_flag);
}
