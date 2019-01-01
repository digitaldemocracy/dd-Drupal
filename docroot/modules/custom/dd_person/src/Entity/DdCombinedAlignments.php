<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;

/**
 * Defines the DD Combined Alignments entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_combined_alignments",
 *   label = @Translation("DD Combined Alignments"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_person\DdCombinedAlignmentsListBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdCombinedAlignmentsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_person\Form\DdCombinedAlignmentsForm",
 *       "add" = "Drupal\dd_person\Form\DdCombinedAlignmentsForm",
 *       "edit" = "Drupal\dd_person\Form\DdCombinedAlignmentsForm",
 *       "delete" = "Drupal\dd_person\Form\DdCombinedAlignmentsDeleteForm",
 *     },
 *     "access" = "Drupal\dd_person\DdCombinedAlignmentsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_person\DdCombinedAlignmentsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "CombinedAlignmentScores",
 *   admin_permission = "administer dd combined alignments entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "vid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_combined_alignments/{dd_combined_alignments}",
 *     "add-form" = "/admin/structure/dd_combined_alignments/add",
 *     "edit-form" = "/admin/structure/dd_combined_alignments/{dd_combined_alignments}/edit",
 *     "delete-form" = "/admin/structure/dd_combined_alignments/{dd_combined_alignments}/delete",
 *     "collection" = "/admin/structure/dd_combined_alignments",
 *   },
 *   field_ui_base_route = "dd_combined_alignments.settings"
 * )
 */
class DdCombinedAlignments extends DdPersonContentEntityBase implements DdCombinedAlignmentsInterface {

  /**
   * Building a list of options for the organization field.
   *
   * @return array
   *   Key value pairs for organization options.
   */
  public static function buildOrganizationList() {
    // Get the data.
    $query = Database::getConnection('default', DdBase::getDddbName())->select('OrgConcept', 'oc');
    $query->fields('oc', ['oid', 'name']);
    // $query->condition('oc.state', DdBase::getCurrentState());
    $query->condition('oc.meter_flag', 1);
    $query->orderBy('oc.name');
    $orgs = $query->execute()->fetchAll();

    $org_options = ['' => '- Select an Organization -'];
    foreach ($orgs as $org) {
      // Populate option.
      $id = $org->oid;
      $value = $org->name;
      $org_options[$id] = $value;
    }
    return $org_options;
  }

  /**
   * Building a list of options for the legislator field.
   *
   * @return array
   *   Key value pairs for legislator options.
   */
  public static function buildLegislatorList() {
    // Get the data.
    $query = Database::getConnection('default', DdBase::getDddbName())->select('CombinedAlignmentScores', 'cas');
    $query->fields('cas', ['house', 'party', 'pid_house_party']);
    $query->leftJoin('Person', 'p', 'p.pid=cas.pid');
    $query->fields('p', ['last', 'first']);
    $query->condition('cas.state', DdBase::getCurrentState());
    $query->distinct();
    $query->orderBy('p.last');
    $query->orderBy('cas.house');
    $query->orderBy('cas.party');
    $query->orderBy('cas.pid_house_party', 'DESC');
    $legs = $query->execute()->fetchAll();

    $leg_options = [];
    $leg_options['All'] = '';
    // Process the parties first.
    foreach ($legs as $leg) {
      $pid_house_party = explode("_", $leg->pid_house_party);
      if ($pid_house_party[0] != '' && !is_numeric($pid_house_party[0])) {
        $id = $leg->pid_house_party;
        if ($pid_house_party[1] != 'nan') {
          $value = $pid_house_party[1] . " " . $pid_house_party[2];
        } else {
          $value = 'All';
          $id = 'All';
        }

        $value = preg_replace('/(Democrat|Republican)/', '\1s', $value);
        $leg_options[$id] = $value;
      }
      // Processs the people next.
      if (is_numeric($pid_house_party[0])) {
      //if (is_numeric($leg->pid_house_party)) {
        $id = $leg->pid_house_party;
        $value = $leg->last . ', ' . $leg->first;
        $leg_options[$id] = $value;
      }
    }
    // Process the people next.
    /*
    foreach ($legs as $leg) {
      drupal_set_message($leg->pid_house_party);
      if (is_numeric($leg->pid_house_party)) {
        $id = $leg->pid_house_party;
        $value = $leg->last . ', ' . $leg->first;
        $leg_options[$id] = $value;
      }
    }
    */
    //drupal_set_message(print_r($leg_options,1));
    return $leg_options;
  }

  public static function buildSessionYearList() {
    $query = Database::getConnection('default', DdBase::getDddbName())->select('CombinedAlignmentScores', 'cas');
    $query->fields('cas', ['session_year']);
    $query->distinct();
    $query->orderBy('cas.session_year', 'DESC');
    $results = $query->execute()->fetchAll();

    $years = [];
    foreach ($results as $result) {
      $id = $result->session_year;
      $value = $result->session_year;
      if ($id === 'All') {
        $years['ALL'] = $value;
      } else {
        $next_year = $value + 1;

        $years[$id] = $value . '-' . $next_year;
      }
    }
    return $years;
  }

  /**
   * {@inheritdoc}
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOid() {
    return $this->get('oid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setOid($oid) {
    $this->set('oid', $oid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHouse() {
    return $this->get('house')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHouse($house) {
    $this->set('house', $house);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getParty() {
    return $this->get('party')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setParty($party) {
    $this->set('party', $party);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getScore() {
    return $this->get('score')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setScore($score) {
    $this->set('score', $score);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPositionsRegistered() {
    return $this->get('positions_registered')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPositionsRegistered($positions) {
    $this->set('positions_registered', $positions);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVotesInAgreement() {
    return $this->get('votes_in_agreement')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVotesInAgreement($votes) {
    $this->set('votes_in_agreement', $votes);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVotesInDisagreement() {
    return $this->get('votes_in_disagreement')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVotesInDisagreement($votes) {
    $this->set('votes_in_disagreement', $votes);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPidHouseParty() {
    return $this->get('pid_house_party')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPidHouseParty($pid_house_party) {
    $this->set('pid_house_party', $pid_house_party);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  public function setNoAbstainVotes($no_abstain_votes) {
    $this->set('no_abstain_votes', $no_abstain_votes);
  }

  public function getNoAbstainVotes() {
    return $this->get('no_abstain_votes')->value;
  }

  public function setNoResolutions($no_resolutions) {
    $this->set('no_resolutions', $no_resolutions);
  }

  public function getNoResolutions() {
    return $this->get('no_resolutions')->value;
  }

  public function setNoUnanimous($no_unanimous) {
    $this->set('no_unanimous', $no_unanimous);
  }

  public function getNoUnanimous() {
    return $this->get('no_unanimous')->value;
  }

  public function setSessionYear($session_year) {
    $this->set('session_year', $session_year);
  }

  public function getSessionYear() {
    return $this->get('session_year')->value;
  }

  public function setRank() {

  }

  public function getRank() {
    return $this->get('rank')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['oid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Organization ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['party'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Party'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pid_house_party'] = BaseFieldDefinition::create('string')
      ->setLabel(t('PID / House and Party'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['score'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Score'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['positions_registered'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Positions Registered'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['votes_in_agreement'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Votes In Agreement'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['votes_in_disagreement'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Votes In Disagreement'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['no_abstain_votes'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('No Abstain Votes'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['no_resolutions'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('No Resolutions'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['no_unanimous'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('No Unanimous'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session_year'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rank'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('MY ORDER'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
