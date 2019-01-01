<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Bill Discussion entities.
 */
class DdBillDiscussionViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['BillDiscussion']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD BillDiscussion'),
      'help' => $this->t('The DD BillDiscussion Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['BillDiscussion']['bid_leg_participation'] = array(
      'title' => t('Link to LegParticipation bid'),
      'help' => t('Link to LegParticipation bid'),
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
        'base' => 'LegParticipation',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => t('BillDiscussion LegParticipation'),
      ),
    );

    $data['BillDiscussion']['speaker_start_time'] = array(
      'title' => t('Speaker Start Time'),
      'help' => t('Speaker Start Time'),
      'field' => array(
        'id' => 'bill_discussion_speaker_start_time',
      ),
    );
    $data['BillDiscussion']['speaker_start_video_file_id'] = array(
      'title' => t('Speaker Start Video File Id'),
      'help' => t('Speaker Start Video File Id'),
      'field' => array(
        'id' => 'bill_discussion_speaker_start_video_id',
      ),
    );
    return $data;
  }

}
