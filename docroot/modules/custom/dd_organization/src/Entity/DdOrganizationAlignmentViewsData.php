<?php

namespace Drupal\dd_organization\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD OrganizationAlignment entities.
 */
class DdOrganizationAlignmentViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['OrgAlignments']['table']['base'] = array(
      'field' => 'oa_id',
      'title' => $this->t('DD OrgAlignments'),
      'help' => $this->t('The DD OrgAlignments oa_id.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['OrgAlignments']['oid'] = array(
      'title' => t('Link to Organization oid'),
      'help' => t('Link to Organization oid'),
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
        'base' => 'Organizations',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('OrganizationAlignment Organization'),
      ),
    );

    $data['OrgAlignments']['bid'] = array(
      'title' => t('Link to Bill bid'),
      'help' => t('Link to Bill bid'),
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
        'base' => 'Bill',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => t('OrganizationAlignment Bill'),
      ),
    );


    $data['OrgAlignments']['hid'] = array(
      'title' => t('Link to Hearing hid'),
      'help' => t('Link to Hearing hid'),
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
        'base' => 'Hearing',
        'base field' => 'hid',
        'relationship field' => 'hid',
        'id' => 'standard',
        'label' => t('OrganizationAlignment Hearing'),
      ),
    );

    return $data;
  }

}
