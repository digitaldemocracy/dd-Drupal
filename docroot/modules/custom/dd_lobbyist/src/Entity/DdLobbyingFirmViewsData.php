<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD LobbyingFirm entities.
 */
class DdLobbyingFirmViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['LobbyingFirm']['table']['base'] = array(
      'field' => 'filer_naml',
      'title' => $this->t('DD LobbyingFirm'),
      'help' => $this->t('The DD LobbyingFirm filer_naml.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
