<?php

namespace Drupal\dd_committee\Entity;

use Drupal\dd_base\DdBase;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Committee entities.
 */
class DdCommitteeViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Committee']['table']['base'] = array(
      'field' => 'cid',
      'title' => t('DD Committee'),
      'help' => t('The DD Committee ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Committee']['serveson_cid'] = array(
      'title' => t('Committee ServesOn cid'),
      'help' => t('Committee ServesOn cid'),
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
        'base' => 'servesOn',
        'base field' => 'cid',
        'relationship field' => 'cid',
        'id' => 'standard',
        'label' => t('Committee servesOn'),
      ),
    );

    $data['Committee']['consultant_serveson_cid'] = array(
      'title' => t('Committee ConsultantServesOn cid'),
      'help' => t('Committee ConsultantServesOn cid'),
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
        'base' => 'ConsultantServesOn',
        'base field' => 'cid',
        'relationship field' => 'cid',
        'id' => 'standard',
        'label' => t('Committee ConsultantServesOn'),
      ),
    );

    $data['CommitteeNames']['table']['join'] = array(
      'Committee' => array(
        'left_field' => 'name',
        'field' => 'name',
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
          1 => array(
            'field' => 'house',
            'left_field' => 'house',
          ),
        ),
      ),
    );

    // Use custom year filter on session year field.
    $data['Committee']['session_year']['filter']['id'] = 'dd_year';

    $data['CommitteeNames']['table']['base'] = array(
      'field' => 'cn_id',
      'title' => t('DD CommitteeNames'),
      'help' => t('The DD CommitteeNames ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );
    $data['CommitteeNames']['table']['group'] = t('CommitteeNames');
    $data['CommitteeNames']['table']['provider'] = 'dd_committee';

    $data['CommitteeNames']['cn_id'] = array(
      'title' => t('Committee Name ID'),
      'help' => t('Committee Name ID'),
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

    return $data;
  }

}
