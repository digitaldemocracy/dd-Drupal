<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\dd_base\DdBase;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD HearingAgenda entities.
 */
class DdHearingAgendaViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['HearingAgenda']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD HearingAgenda'),
      'help' => $this->t('The DD HearingAgenda HID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['HearingAgenda']['bid'] = array(
      'title' => t('Bill bid'),
      'help' => t('Bill bid'),
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
      'relationship' => array(
        'base' => 'Bill',
        'base field' => 'bid',
        'id' => 'standard',
        'label' => t('Bill HearingAgenda'),
      ),
    );

    $data['HearingAgenda']['hid_committees'] = array(
      'title' => t('CommitteeHearings hid'),
      'help' => t('CommitteeHearings hid'),
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
        'base' => 'CommitteeHearings',
        'base field' => 'hid',
        'relationship field' => 'hid',
        'id' => 'standard',
        'label' => t('CommitteeHearings HearingAgenda'),
      ),
    );
    $data['HearingAgenda']['bill_authors'] = array(
      'title' => t('Bill Authors'),
      'help' => t('Bill Authors'),
      'field' => array(
        'id' => 'dd_bill_authors',
      ),
    );

    $data['Hearing']['table']['join']['HearingAgenda'] = array(
      'left_field' => 'hid',
      'field' => 'hid',
      'extra' => array(
        0 => array(
          'field' => 'state',
          'value' => DdBase::getCurrentState(),
        ),
      ),
      'type' => 'INNER',
    );

    return $data;
  }

}
