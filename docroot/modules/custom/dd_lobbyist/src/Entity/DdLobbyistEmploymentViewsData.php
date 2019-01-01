<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyistEmployment entities.
 */
class DdLobbyistEmploymentViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyistEmployment']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyistEmployment'),
      'help' => $this->t('The DD LobbyistEmployment Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['LobbyistEmployment']['sender_id'] = array(
      'title' => t('LobbyistEmployment sender_id'),
      'help' => t('LobbyistEmployment sender_id'),
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
        'label' => t('LobbyistEmployment sender_id'),
      ),
    );

    return $data;
  }

}
