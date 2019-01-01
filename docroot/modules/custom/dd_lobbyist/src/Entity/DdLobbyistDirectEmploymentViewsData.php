<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyistDirectEmployment entities.
 */
class DdLobbyistDirectEmploymentViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyistDirectEmployment']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyistDirectEmployment'),
      'help' => $this->t('The DD LobbyistDirectEmployment Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['LobbyistDirectEmployment']['sender_id'] = array(
      'title' => t('LobbyistDirectEmployment sender_id'),
      'help' => t('LobbyistDirectEmployment sender_id'),
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
        'label' => t('LobbyistDirectEmployment sender_id'),
      ),
    );

    return $data;
  }

}
