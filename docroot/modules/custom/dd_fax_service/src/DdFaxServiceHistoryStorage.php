<?php

namespace Drupal\dd_fax_service;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class DdFaxServiceHistoryStorage extends SqlContentEntityStorage implements DdFaxServiceHistoryStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(DdFaxServiceHistoryInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {dd_fax_service_history_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {dd_fax_service_history_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(DdFaxServiceHistoryInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {dd_fax_service_history_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('dd_fax_service_history_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
