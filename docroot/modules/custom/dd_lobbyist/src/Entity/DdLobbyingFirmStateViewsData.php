<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyingFirmState entities.
 */
class DdLobbyingFirmStateViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyingFirmState']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyingFirmState'),
      'help' => $this->t('The DD LobbyingFirmState Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['LobbyingFirmState']['filer_id'] = array(
      'title' => t('LobbyingFirmState filer_id'),
      'help' => t('LobbyingFirmState filer_id'),
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
        'label' => t('LobbyingFirmState filer_id'),
      ),
    );

    $data['LobbyingFirmState']['filer_naml'] = array(
      'title' => t('LobbyingFirmState filer_naml'),
      'help' => t('LobbyingFirmState filer_naml'),
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
        'base' => 'LobbyingFirm',
        'base field' => 'filer_naml',
        'relationship field' => 'filer_naml',
        'id' => 'standard',
        'label' => t('LobbyingFirmState filer_naml'),
      ),
    );

    return $data;
  }

}
