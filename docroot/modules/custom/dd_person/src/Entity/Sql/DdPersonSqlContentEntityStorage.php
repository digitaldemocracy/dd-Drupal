<?php

namespace Drupal\dd_person\Entity\Sql;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;
use Drupal\dd_person\Entity\DdPerson;

/**
 * Class DdPersonSqlContentEntityStorage
 * @package Drupal\dd_person\Entity\Sql
 */
class DdPersonSqlContentEntityStorage extends DdBaseSqlContentEntityStorage {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function loadMultiple(array $ids = NULL) {
    $entities = parent::loadMultiple($ids);
    foreach ($entities as $entity) {
      /** @var DdPerson $entity * */
      $entity->setFullNameLastFirst();
      $entity->setFullNameFirstLast();
    }

    // Only add computed fields to Search API Indexing.
    if (DdBase::checkCallingClass(array('Drupal\search_api\Entity\Index', 'Drupal\search_api\Task\IndexTaskManager'))) {
      $year = date('Y');
      foreach ($entities as $entity) {
        if (preg_match('/^[^a-zA-Z]/', $entity->getLastName())) {
          $entity->setLastNameAlpha('#');
        }
        else {
          $entity->setLastNameAlpha(substr($entity->getLastName(), 0, 1));
        }

        $classifications = DdPerson::getClassificationsForPid($entity->id());

        $affiliations_list = array();
        $affiliations_oids = array();
        $current_affiliation_list = array();
        $current_affiliation_oids = array();

        $classification_list = array();
        $classification_dr_ids = array();
        $current_classification_list = array();
        $current_classification_dr_ids = array();

        $affiliations = DdPerson::getAffiliationsForPid($entity->id(), FALSE);
        if ($affiliations) {
          foreach ($affiliations as $affiliation) {
            $affiliations_list[$affiliation->name] = $affiliation->name;
            $affiliations_oids[$affiliation->oid] = $affiliation->oid;
          }
        }

        if ($classifications) {
          foreach ($classifications as $classification) {
            $classification_list[$classification->PersonType] = $classification->PersonType;
            $classification_dr_ids[$classification->dr_id] = $classification->dr_id;

            if ($classification->is_current == '1') {
              $current_classification_list[$classification->PersonType] = $classification->PersonType;
              $current_classification_dr_ids[$classification->dr_id] = $classification->dr_id;

              // Get affiliations for this year.
              $current_affiliations = DdPerson::getAffiliationsForPid($entity->id(), $year);
              if ($current_affiliations) {
                foreach ($current_affiliations as $current_affiliation) {
                  $current_affiliation_list[$current_affiliation->name] = $current_affiliation->name;
                  $current_affiliation_oids[$current_affiliation->oid] = $current_affiliation->oid;
                }
              }
            }
          }
        }

        $entity->setAffiliations(array_values($affiliations_list));
        $entity->setClassifications(array_values($classification_list));
        $entity->setAffiliationOids(array_values($affiliations_oids));
        $entity->setClassificationDrIds(array_values($classification_dr_ids));
        $entity->setCurrentAffiliations(array_values($current_affiliation_list));
        $entity->setCurrentClassifications(array_values($current_classification_list));
        $entity->setCurrentAffiliationOids(array_values($current_affiliation_oids));
        $entity->setCurrentClassificationDrIds(array_values($current_classification_dr_ids));
        $entity->setHasSpoken(DdPerson::hasSpokenForPid($entity->id()));
      }
    }
    return $entities;
  }
}
