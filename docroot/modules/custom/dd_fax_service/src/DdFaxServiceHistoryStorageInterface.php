<?php

namespace Drupal\dd_fax_service;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface;

/**
 * Defines the storage handler class for Dd fax service history entities.
 *
 * This extends the base storage class, adding required special handling for
 * Dd fax service history entities.
 *
 * @ingroup dd_fax_service
 */
interface DdFaxServiceHistoryStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Dd fax service history revision IDs for a specific Dd fax service history.
   *
   * @param \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface $entity
   *   The Dd fax service history entity.
   *
   * @return int[]
   *   Dd fax service history revision IDs (in ascending order).
   */
  public function revisionIds(DdFaxServiceHistoryInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Dd fax service history author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Dd fax service history revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface $entity
   *   The Dd fax service history entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(DdFaxServiceHistoryInterface $entity);

  /**
   * Unsets the language for all Dd fax service history with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
