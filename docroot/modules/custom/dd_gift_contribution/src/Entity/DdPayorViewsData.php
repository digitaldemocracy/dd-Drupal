<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Payor entities.
 */
class DdPayorViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Payors']['table']['base'] = array(
      'field' => 'prid',
      'title' => $this->t('DD Payor'),
      'help' => $this->t('The DD Payor ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
