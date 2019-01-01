<?php

namespace Drupal\dd_utterance\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Utterance entities.
 */
class DdUtteranceViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Utterance']['table']['base'] = array(
      'field' => 'uid',
      'title' => t('DD Utterance'),
      'help' => t('The DD Utterance uid'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
