<?php

namespace Drupal\dd_utterance\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Initial Utterance entities.
 */
class DdInitialUtteranceViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['InitialUtterance']['table']['base'] = array(
      'field' => 'uid',
      'title' => t('DD Initial Utterance'),
      'help' => t('The DD Initial Utterance uid'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['InitialUtterance']['table']['join'] = array(
      'CombinedRepresentations' => array(
        'left_field' => 'pid',
        'field' => 'pid',
        'extra' => array(
          0 => array(
            'field' => 'did',
            'left_field' => 'did',
          ),
        ),
        'type' => 'LEFT',
      ),
      'ConsultantServesOn' => array(
        'left_field' => 'pid',
        'field' => 'pid',
        'type' => 'INNER',
      ),
    );

    return $data;
  }

}
