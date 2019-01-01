<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyistRepresentation entities.
 */
class DdLobbyistRepresentationViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyistRepresentation']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD LobbyistRepresentation'),
      'help' => $this->t('The DD LobbyistRepresentation Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
