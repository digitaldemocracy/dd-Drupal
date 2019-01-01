<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_lobbyist\Entity\DdCombinedRepresentations;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\Query;

/**
 * Defines the DD Person entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_person",
 *   label = @Translation("DD Person"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_person\DdPersonListBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdPersonViewsData",
 *     "translation" = "Drupal\dd_person\DdPersonTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_person\Form\DdPersonForm",
 *       "edit" = "Drupal\dd_person\Form\DdPersonForm",
 *     },
 *     "access" = "Drupal\dd_person\DdPersonAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_person\DdPersonHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Person",
 *   fieldable = FALSE,
 *   translatable = FALSE,
 *   admin_permission = "administer dd_person entities",
 *   entity_keys = {
 *     "id" = "pid",
 *   },
 *   links = {
 *     "canonical" = "/person/{dd_person}",
 *     "edit-form" = "/person/{dd_person}/edit",
 *     "collection" = "/admin/structure/dd_person",
 *     "settings" = "/admin/structure/dd_person_settings",
 *   },
 *   field_ui_base_route = "dd_person.settings"
 * )
 */
class DdPerson extends DdPersonContentEntityBase implements DdPersonInterface {

  /**
   * Get Pids for a first/last name.
   *
   * @param string $last
   *   Last name
   * @param string $first
   *   First name
   *
   * @return array
   *   Array of PIDs.
   */
  public static function getPidByName($last, $first) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Person', 'p');
    $query->condition('p.last', $last);
    $query->condition('p.first', $first);
    $query->join('PersonStateAffiliation', 'psa', 'psa.pid = p.pid');
    $query->condition('psa.state', DdBase::getCurrentState());
    $query->fields('p', array('pid'));
    $pids = $query->execute()->fetchCol();
    return $pids;
  }

  /**
   * Determine affiliations for a person during a year.
   *
   * Only applied to Lobbyist / General Public.
   *
   * @param int $pid
   *   Person ID
   * @param int $year
   *   Year to query, if FALSE shows all.
   *
   * @return array
   *   Array of affiliation objects containing OID and Name.
   */
  public static function getAffiliationsForPid($pid, $year) {
    $affiliations = array();
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CombinedRepresentations', 'cr');
    $query->condition('cr.pid', $pid);
    $query->fields('cr', array('dr_id'));
    if ($year) {
      $query->condition('cr.year', $year);
    }
    else {
      $session_year = DdBase::getSessionYear(date("Y",strtotime("now")));
      $years = [$session_year, $session_year + 1];
      $query->condition('cr.year', $years, 'IN');
    }
    $ids = $query->execute()->fetchCol();
    if ($ids) {
      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CombinedRepresentations', 'cr');
      $query->condition('cr.dr_id', $ids, 'IN');
      $query->join('Organizations', 'o', 'o.oid = cr.oid');
      $query->fields('o', array('name', 'oid'));
      $query->groupBy('o.oid');
      $query->orderBy('o.name');

      $affiliations = $query->execute()->fetchAll();
    }

    return $affiliations;
  }

  /**
   * Get classifications for a PID.
   *
   * @param int $pid
   *   PID to get classifications for.
   * @param int $show_current_former
   *   1 to show only current, 0 to show only former, -1 to show all.
   * @param int $year
   *   Filter to year specified.
   * @param string $state
   *   State override, if empty uses current state.
   *
   * @return array
   *   Array of PersonClassifications objects.
   */
  public static function getClassificationsForPid($pid, $show_current_former = -1, $year = NULL, $state = '') {
    if ($state == '') {
      $state = DdBase::getCurrentState();
    }
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('PersonClassifications', 'pc');
    $query->condition('pc.pid', $pid);
    $query->condition('pc.state', $state);
    if ($show_current_former != -1) {
      $query->condition('pc.is_current', $show_current_former);
    }
    elseif ($year) {
      // Narrow former to years.
      $query->condition('pc.specific_year', $year);
    }
    if ($show_current_former == -1) {
      $query->orderBy('pc.is_current', 'DESC');
    }
    $query->fields('pc', ['PersonType', 'dr_id', 'is_current', 'session_year', 'specific_year']);
    $results = $query->execute()->fetchAll();

    return $results;
  }

  /**
   * Determine employers for a person during a year.
   *
   * @param int $pid
   *   Person ID
   * @param int $year
   *   Year to query, if FALSE shows all.
   *
   * @return array
   *   Array of objects containing name.
   */
  public static function getKnownEmployersForPid($pid, $year) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CombinedLobbyistEmployers', 'cle');
    $query->condition('cle.pid', $pid);
    if ($year) {
      $query->condition('cle.ls_beg_yr', $year, '<=');
      $query->condition('cle.ls_end_yr', $year, '>=');
    }
    $query->isNotNull('cle.assoc_name');
    $query->addField('cle', 'assoc_name', 'name');
    $query->groupBy('cle.assoc_name');
    $combined_lobbyist_employers = $query->execute()->fetchAll();

    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('LegislativeStaffRepresentation', 'lsr');
    $query->condition('lsr.pid', $pid);
    $query->leftJoin('Committee', 'c', 'c.cid = lsr.committee');
    $query->leftJoin('Legislator', 'l', 'l.pid = lsr.legislator');
    $query->leftJoin('Person', 'p', 'p.pid = l.pid');

    if ($year) {
      $query->join('Hearing', 'h', 'h.hid = lsr.hid');
      $query->condition('h.session_year', DdBase::getSessionYear($year));
    }

    $query->addExpression("IF(c.name IS NOT NULL,c.name,CONCAT('Office of ', p.first, ' ' , p.last))", "name");
    $query->fields('c', array('cid'));
    $query->isNotNull('name');
    $query->groupBy('name');
    $query->groupBy('first');
    $query->groupBy('last');
    $query->groupBy('cid');
    $legislative_staff_representation = $query->execute()->fetchAll();

    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('StateAgencyRepRepresentation', 'sarr');
    $query->condition('sarr.pid', $pid);
    $query->isNotNull('sarr.employer');

    if ($year) {
      $query->join('Hearing', 'h', 'h.hid = sarr.hid');
      $query->condition('h.session_year', DdBase::getSessionYear($year));
    }

    $query->addField('sarr', 'employer', 'name');
    $query->groupBy('sarr.employer');
    $state_agency_rep_representation = $query->execute()->fetchAll();

    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('StateConstOfficeRepRepresentation', 'scorr');
    $query->condition('scorr.pid', $pid);
    $query->isNotNull('scorr.office');

    if ($year) {
      $query->join('Hearing', 'h', 'h.hid = scorr.hid');
      $query->condition('h.session_year', DdBase::getSessionYear($year));
    }

    $query->addField('scorr', 'office', 'name');
    $query->groupBy('scorr.office');
    $state_const_office_rep_representation = $query->execute()->fetchAll();

    $results = array_merge($combined_lobbyist_employers, $legislative_staff_representation, $state_agency_rep_representation, $state_const_office_rep_representation);

    usort($results, function($a, $b)
    {
      return strcmp($a->name, $b->name);
    });

    return $results;
  }

  /**
   * Check if current person entity is a speaker.
   *
   * @param int $pid
   *   Person ID
   *
   * @return bool
   *   Boolean of if person has spoken.
   */
  public static function hasSpokenForPid($pid) {
    $classifications = self::getClassificationsForPid($pid);
    if ($classifications) {
      foreach ($classifications as $classification) {
        if ($classification->PersonType == 'Legislator') {
          // If ever has been a legislator, set has spoken.
          return TRUE;
        }
      }
    }
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('InitialUtterance', 'iu');
    $query->condition('iu.pid', $pid);
    return (bool) $query->countQuery()->execute()->fetchField();
  }

  /**
   * Get Person matches for search term.
   *
   * @param string $term
   *   Term to search by
   * @param int $num_matches
   *   Max # of matches to return.
   * @param string $state
   *   State override, if empty uses current state.
   * @param bool $use_solr
   *   If TRUE, uses Solr, otherwise uses db query.
   *
   * @return array
   *   Array of key/value matches
   */
  public static function getPersonMatches($term, $num_matches = 4, $state = '', $use_solr = TRUE) {
    if ($state == '') {
      $state = DdBase::getCurrentState();
    }

    // Get Person Matches
    // Firstname.
    // Lastname.
    // Last, first.
    if (preg_match("/([-a-zA-Z]+)[,\\s]+([-a-zA-Z]+)|([-a-zA-Z]+)/", $term, $person_matches)) {
      $term_first = '';
      $term_last = '';
      $term_last_or_first = '';
      if ($person_matches[2]) {
        $term_first = $person_matches[2];
        $term_last = $person_matches[1];
      }
      else {
        $term_last_or_first = $person_matches[3];
      }

      if ($use_solr) {

        // @todo Determine how to switch solr servers for state override.
        // Perform search against Solr index.
        $index = Index::load('persons_index');

        $person_query = new Query($index);
        $person_query->sort('last');
        $person_query->range(0, $num_matches);
        $person_query->addCondition('has_spoken', TRUE);
        // @todo add OR group support if possible for first/last name.
        $person_query->addCondition('last', $term_last);
        if ($term_first != '') {
          $person_query->addCondition('first', $term_first);
        }

        $persons = [];
        $results = $person_query->execute();
        if ($results->getResultCount()) {
          foreach ($results->getResultItems() as $key => $item) {
            $person = new \stdClass();
            $person->pid = $item->getField('pid')->getValues()[0];
            $person->first = $item->getField('first')->getValues()[0];
            $person->last = $item->getField('last')->getValues()[0];
            $person->types = implode(', ', $item->getField('person_type')
              ->getValues());
            $person->fullname = $person->last . ', ' . $person->first;
            $person->type_label = '';
            if ($person->types != '') {
              $person->type_label = ' (Speaker - ' . $person->types . ')';
            }

            if (!in_array('Unlabeled', $item->getField('person_type')
              ->getValues())
            ) {
              $persons[] = $person;
            }
          }
        }
      }
      else {
        $person_query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Person');
        $person_query->fields('Person', ['pid', 'first', 'last']);
        $person_query->join('PersonStateAffiliation', 'psa', 'psa.pid = Person.pid');
        $person_query->join('InitialUtterance', 'iu', 'iu.pid = Person.pid');
        $person_query->condition('psa.state', $state);
        $person_query->orderBy('last');
        $person_query->range(0, $num_matches);

        if ($term_last_or_first != '') {
          $or_group = $person_query->orConditionGroup();
          $or_group->condition('last', $term_last_or_first . '%', 'LIKE');
          $or_group->condition('first', $term_last_or_first . '%', 'LIKE');
          $person_query->condition($or_group);
        }
        else {
          $and_group_fl = $person_query->andConditionGroup();
          $and_group_fl->condition('first', $term_first . '%', 'LIKE');
          $and_group_fl->condition('last', $term_last . '%', 'LIKE');

          $and_group_lf = $person_query->andConditionGroup();
          $and_group_lf->condition('first', $term_last . '%', 'LIKE');
          $and_group_lf->condition('last', $term_first . '%', 'LIKE');

          $or_group = $person_query->orConditionGroup();
          $or_group->condition($and_group_fl);
          $or_group->condition($and_group_lf);
          $person_query->condition($or_group);
        }
        $person_query->groupBy('Person.pid');

        $persons = [];
        $results = $person_query->execute()->fetchAll();
        if ($results) {
          foreach ($results as $person) {
            $person_types = [];
            $person->types = NULL;
            $person_classifications = DdPerson::getClassificationsForPid($person->pid, -1, NULL, $state);
            if ($person_classifications) {
              foreach ($person_classifications as $person_classification) {
                $person_types[$person_classification->PersonType] = $person_classification->PersonType;
              }
              $person->types = implode(', ', $person_types);
            }

            $person->fullname = $person->last . ', ' . $person->first;
            $person->type_label = '';
            if ($person->types != '') {
              $person->type_label = ' [Speaker - ' . $person->types . ']';
            }

            if (!in_array('Unlabeled', $person_types)
            ) {
              $persons[] = $person;
            }
          }
        }
      }
      return $persons;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getHasSpoken() {
    return $this->get('has_spoken')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHasSpoken($has_spoken) {
    $this->set('has_spoken', $has_spoken);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    // 2998 - Committee Secretary.
    $exclude_ids = [
      2998,
    ];

    if (in_array($this->id(), $exclude_ids)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getClassifications() {
    return $this->get('classifications')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setClassifications($classifications) {
    $this->set('classifications', $classifications);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentClassifications() {
    return $this->get('current_classifications')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentClassifications($classifications) {
    $this->set('current_classifications', $classifications);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAffiliations() {
    return $this->get('affiliations')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setAffiliations($affiliations) {
    $this->set('affiliations', $affiliations);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentAffiliations() {
    return $this->get('current_affiliations')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentAffiliations($affiliations) {
    $this->set('current_affiliations', $affiliations);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getClassificationDrIds() {
    return $this->get('classifications_dr_ids')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setClassificationDrIds($classification_dr_ids) {
    $this->set('classification_dr_ids', $classification_dr_ids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentClassificationDrIds() {
    return $this->get('current_classifications_dr_ids')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentClassificationDrIds($classification_dr_ids) {
    $this->set('current_classification_dr_ids', $classification_dr_ids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAffiliationOids() {
    return $this->get('affiliation_oids')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setAffiliationOids($affiliation_oids) {
    $this->set('affiliation_oids', $affiliation_oids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentAffiliationOids() {
    return $this->get('current_affiliation_oids')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentAffiliationOids($affiliation_oids) {
    $this->set('current_affiliation_oids', $affiliation_oids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastNameAlpha() {
    return $this->get('last_name_alpha')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastNameAlpha($last_name_alpha) {
    $this->set('last_name_alpha', $last_name_alpha);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getFirstName() . ' ' . $this->getLastName();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['first']->setComputed(FALSE);
    $fields['middle']->setComputed(FALSE);
    $fields['suffix']->setComputed(FALSE);
    $fields['title']->setComputed(FALSE);
    $fields['last']->setComputed(FALSE);
    $fields['image']->setComputed(FALSE);

    // Computed fields.
    $fields['has_spoken'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Has Spoken'))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['classifications'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Classifications'))
      ->setDefaultValue(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
         'label' => 'inline',
         'type' => 'string',
         'weight' => 70,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['classification_dr_ids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Classifications Drupal IDs'))
      ->setSetting('target_type', 'dd_person_classifications')
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['current_classifications'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Current Classifications'))
      ->setDefaultValue(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 70,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['current_classification_dr_ids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Current Classifications Drupal IDs'))
      ->setSetting('target_type', 'dd_person_classifications')
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['affiliations'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Affiliations'))
      ->setDefaultValue(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 70,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['affiliation_oids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Affiliations Organizations'))
      ->setSetting('target_type', 'dd_organization')
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['current_affiliations'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Current Affiliations'))
      ->setDefaultValue(FALSE)
      ->setCardinality(-1)
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 70,
      ))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['current_affiliation_oids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Current Affiliations Organizations'))
      ->setSetting('target_type', 'dd_organization')
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['last_name_alpha'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name First Char (#, A-Z)'))
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    return $fields;
  }
}
