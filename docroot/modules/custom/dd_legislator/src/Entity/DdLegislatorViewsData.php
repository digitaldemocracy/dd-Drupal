<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Legislator entities.
 */
class DdLegislatorViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Legislator']['table']['base'] = [
      'field' => 'pid',
      'title' => $this->t('DD Legislator'),
      'help' => $this->t('The Legislator PID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    ];

    $data['Legislator']['pid_term'] = [
      'title' => t('Legislator Term pid'),
      'help' => t('Legislator Term pid.'),
      'field' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'standard',
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
      'relationship' => [
        'base' => 'Term',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'Legislator Term',
      ],
    ];

    $data['Legislator']['committees'] = [
      'title' => t('Legislator Committees Plugin'),
      'help' => t('Get list of current committees legislator serves on'),
      'field' => [
        'id' => 'dd_legislator_committees',
      ],
    ];
    $data['Legislator']['leg_info'] = array(
      'title' => t('Legislator Info Plugin'),
      'help' => t('Legislator Info, such as house/party/district'),
      'field' => array(
        'id' => 'dd_legislator_info',
      ),
    );
    return $data;
  }
}
