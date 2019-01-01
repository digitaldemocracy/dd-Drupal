<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyingContracts entities.
 */
class DdLobbyingContractsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyingContracts']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyingContracts'),
      'help' => $this->t('The DD LobbyingContracts Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['LobbyingContracts']['filer_id'] = array(
      'title' => t('LobbyingContracts filer_id'),
      'help' => t('LobbyingContracts filer_id'),
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
        'label' => t('LobbyingContracts filer_id'),
      ),
    );

    $data['LobbyingContracts']['sender_id'] = array(
      'title' => t('LobbyingContracts sender_id'),
      'help' => t('LobbyingContracts sender_id'),
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
        'relationship field' => 'sender_id',
        'id' => 'standard',
        'label' => t('LobbyingContracts sender_id'),
      ),
    );

    return $data;
  }

}
