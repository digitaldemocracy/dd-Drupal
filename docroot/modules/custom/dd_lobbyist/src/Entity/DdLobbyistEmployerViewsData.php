<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyistEmployer entities.
 */
class DdLobbyistEmployerViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyistEmployer']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyistEmployer'),
      'help' => $this->t('The DD LobbyistEmployer Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['LobbyistEmployer']['filer_id'] = array(
      'title' => t('LobbyistEmployer filer_id'),
      'help' => t('LobbyistEmployer filer_id'),
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
        'base' => 'Lobbyist',
        'base field' => 'filer_id',
        'relationship field' => 'filer_id',
        'id' => 'standard',
        'label' => t('LobbyistEmployer filer_id'),
      ),
    );

    $data['LobbyistEmployer']['oid'] = array(
      'title' => t('LobbyistEmployer Organizations oid'),
      'help' => t('LobbyistEmployer Organizations oid'),
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
        'base' => 'Organizations',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('LobbyistEmployer Organizations'),
      ),
    );

    return $data;
  }

}
