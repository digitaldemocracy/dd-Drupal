<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_base\DdBase;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Person entities.
 */
class DdPersonViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Person']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD Person'),
      'help' => $this->t('The DD Person PID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Person']['person_classifications'] = array(
      'title' => t('Person Classifications'),
      'help' => t('Person Classifications'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'PersonClassifications',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'PersonClassifications Person',
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
        ),
      ),
    );

    $data['Person']['person_affiliations'] = array(
      'title' => t('Person Affiliations'),
      'help' => t('Person Affiliations'),
      'field' => array(
        'id' => 'dd_person_affiliations',
      ),
    );

    $data['Person']['person_classifications_field'] = array(
      'title' => t('Person Classifications Custom Field'),
      'help' => t('Person Classifications Custom Field'),
      'field' => array(
        'id' => 'dd_person_classifications',
      ),
    );

    $data['Person']['dd_lobbyist_known_clients'] = array(
      'title' => t('Lobbyist Known Clients Custom Field'),
      'help' => t('Lobbyist Known Clients Custom Field'),
      'field' => array(
        'id' => 'dd_lobbyist_known_clients',
      ),
    );

    $data['Person']['dd_person_known_employers'] = array(
      'title' => t('Person Known Employers Custom Field'),
      'help' => t('Person Known Employers Custom Field'),
      'field' => array(
        'id' => 'dd_person_known_employers',
      ),
    );

    $data['Person']['dd_person_has_spoken'] = array(
      'title' => t('Person Has Spoken Custom Field'),
      'help' => t('Person Has Spoken Custom Field'),
      'field' => array(
        'id' => 'dd_person_has_spoken',
      ),
    );

    $data['Person']['pid_general_public'] = array(
      'title' => t('Person General Publicpid'),
      'help' => t('Person General Publicpid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'GeneralPublic',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'Person General Public',
      ),
    );

    $data['Person']['pid_legislator'] = array(
      'title' => t('Person Legislator pid'),
      'help' => t('Person Legislator pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Legislator',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'Person Legislator',
      ),
    );

    $data['Person']['pid_legstaff'] = array(
      'title' => t('Person Legislative Staff pid'),
      'help' => t('Person Legislative Staff pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'LegislativeStaff',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => t('Person Legislative Staff'),
      ),
    );

    $data['Person']['pid_lobbyist'] = array(
      'title' => t('Person Lobbyist pid'),
      'help' => t('Person Lobbyist pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Lobbyist',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => t('Person Lobbyist'),
      ),
    );

    $data['Person']['pid_stateagencyreprepresentation'] = array(
      'title' => t('Person StateAgencyRepRepresentation pid'),
      'help' => t('Person StateAgencyRepRepresentation pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'StateAgencyRepRepresentation',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'Person StateAgencyRepRepresentation',
      ),
    );

    $data['Person']['PersonTestifiedFor'] = array(
      'title' => t('Person Testified For'),
      'help' => t('Show a list of organizations person testified for'),
      'field' => array(
        'id' => 'testified_for',
      ),
    );

    $data['Person']['PersonTestimonyPosition'] = array(
      'title' => t('Person Testimony Position'),
      'help' => t('Show a list of testimony positions'),
      'field' => array(
        'id' => 'testimony_position',
      ),
    );

    // @todo Remove when StateAgencyRepRepresentation entity is created.
    $data['StateAgencyRepRepresentation']['table']['group'] = t('StateAgencyRepRepresentation');
    $data['StateAgencyRepRepresentation']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD StateAgencyRepRepresentation'),
      'help' => $this->t('The DD StateAgencyRepRepresentation ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['StateAgencyRepRepresentation']['pid'] = array(
      'title' => t('StateAgencyRepRepresentation Pid'),
      'help' => t('StateAgencyRepRepresentation Pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
    );

    $data['StateAgencyRepRepresentation']['employer'] = array(
      'title' => t('StateAgencyRepRepresentation Employer'),
      'help' => t('StateAgencyRepRepresentation Employer'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'string',
      ),
      'argument' => array(
        'id' => 'string',
      ),
    );

    $data['StateAgencyRepRepresentation']['position'] = array(
      'title' => t('StateAgencyRepRepresentation Position'),
      'help' => t('StateAgencyRepRepresentation Position'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'string',
      ),
      'argument' => array(
        'id' => 'string',
      ),
    );

    $data['PersonStateAffiliation']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD PersonStateAffiliation'),
      'help' => $this->t('The DD PersonStateAffiliation PID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );
    $data['PersonStateAffiliation']['table']['group'] = $this->t('PersonStateAffiliation Table');

    $data['PersonStateAffiliation']['pid'] = array(
      'title' => t('PersonStateAffiliation PID'),
      'help' => t('PersonStateAffiliation PID'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
    );

    $data['PersonStateAffiliation']['state'] = array(
      'title' => t('PersonStateAffiliation State'),
      'help' => t('PersonStateAffiliation State'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'string',
      ),
      'argument' => array(
        'id' => 'string',
      ),
    );

    $data['PersonStateAffiliation']['table']['join'] = array(
      'Person' => array(
        'left_field' => 'pid',
        'field' => 'pid',
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
        ),
        'type' => 'INNER',
      ),
    );

    return $data;
  }
}
