<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Lobbyist entities.
 */
class DdLobbyistViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Lobbyist']['table']['base'] = array(
      'field' => 'filer_id',
      'title' => $this->t('DD Lobbyist'),
      'help' => $this->t('The DD Lobbyist Drupal filer_id.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Lobbyist']['pid_lobbyistdirectemployment'] = array(
      'title' => t('Lobbyist LobbyistDirectEmployment pid'),
      'help' => t('Lobbyist LobbyistDirectEmployment pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'integer',
      ),
      'argument' => array(
        'id' => 'integer',
      ),
      'relationship' => array(
        'base' => 'LobbyistDirectEmployment',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => t('Lobbyist LobbyistDirectEmployment'),
      ),
    );

    $data['Lobbyist']['filer_id_lobbyistemployer'] = array(
      'title' => t('Lobbyist LobbyistEmployer filer_id'),
      'help' => t('Lobbyist LobbyistEmployer filer_id'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'integer',
      ),
      'argument' => array(
        'id' => 'integer',
      ),
      'relationship' => array(
        'base' => 'LobbyistEmployer',
        'base field' => 'filer_id',
        'relationship field' => 'filer_id',
        'id' => 'standard',
        'label' => t('Lobbyist LobbyistEmployer'),
      ),
    );

    return $data;
  }

}
