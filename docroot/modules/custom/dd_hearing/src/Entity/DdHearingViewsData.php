<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Hearing entities.
 */
class DdHearingViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Hearing']['table']['base'] = array(
      'field' => 'hid',
      'title' => $this->t('DD Hearing'),
      'help' => $this->t('The DD Hearing HID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Hearing']['hid'] = array(
      'title' => t('BillDiscussion hid'),
      'help' => t('BillDiscussion hid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'BillDiscussion',
        'base field' => 'hid',
        'id' => 'standard',
        'label' => t('BillDiscussion Hearing'),
      ),
    );

    $data['Hearing']['state'] = array(
      'title' => t('Hearing State'),
      'help' => t('Hearing State'),
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
    );

    $data['Hearing']['HearingSpeakers'] = array(
      'title' => t('Hearing Speakers'),
      'help' => t('Show a list of speakers'),
      'field' => array(
        'id' => 'hearing_speakers',
      ),
      'filter' => array(
        'id' => 'hearing_speakers',
      ),
    );

    $data['Hearing']['HearingCommitteeAction'] = array(
      'title' => t('Hearing Committee Action'),
      'help' => t('Show a list of committee actions'),
      'field' => array(
        'id' => 'committee_action',
      ),
    );

    $data['Hearing']['HearingTestimonyPosition'] = array(
      'title' => t('Hearing Testimony Position'),
      'help' => t('Show a list of testimony positions'),
      'field' => array(
        'id' => 'testimony_position',
      ),
    );

    $data['Hearing']['HearingTestifiedFor'] = array(
      'title' => t('Hearing Testified For'),
      'help' => t('Show a list of testified for'),
      'field' => array(
        'id' => 'testified_for',
      ),
    );

    $data['BillDiscussion']['bid_billversioncurrent'] = array(
      'title' => t('Link to BillVersionCurrent bid'),
      'help' => t('Link to BillVersionCurrent bid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'BillVersionCurrent',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => t('Bill BillVersionCurrent'),
      ),
    );

    $data['BillDiscussion']['bid_bill'] = array(
      'title' => t('Link to Bill bid'),
      'help' => t('Link to Bill bid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Bill',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => t('Bill BillDiscussion'),
      ),
    );

    $data['BillDiscussion']['hid_hearing'] = array(
      'title' => t('BillDiscussion Hearing hid'),
      'help' => t('BillDiscussion Hearing hid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Hearing',
        'base field' => 'hid',
        'relationship field' => 'hid',
        'id' => 'standard',
        'label' => t('BillDiscussion Hearing'),
      ),
    );

    $data['BillDiscussion']['startVideo_video'] = array(
      'title' => t('Link to Video on startVideo'),
      'help' => t('Link to Video on startVideo'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Video',
        'base field' => 'vid',
        'relationship field' => 'startVideo',
        'id' => 'standard',
        'label' => t('BillDiscussion startVideo Video'),
      ),
    );

    $data['BillDiscussion']['endVideo_video'] = array(
      'title' => t('Link to Video on endVideo'),
      'help' => t('Link to Video on endVideo'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Video',
        'base field' => 'vid',
        'relationship field' => 'endVideo',
        'id' => 'standard',
        'label' => t('BillDiscussion endVideo Video'),
      ),
    );

    $data['BillVoteSummary']['voteId_billvotedetail'] = array(
      'title' => t('BillVoteSummary voteId'),
      'help' => t('BillVoteSummary voteId'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'BillVoteDetail',
        'base field' => 'voteId',
        'relationship field' => 'voteId',
        'id' => 'standard',
        'label' => t('BillVoteSummary BillVoteDetail'),
      ),
    );

    $data['BillDiscussion']['bid_billvotesummary'] = array(
      'title' => t('BillDiscussion BillVoteSummary bid'),
      'help' => t('BillDiscussion BillVoteSummary bid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'BillVoteSummary',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => t('BillDiscussion BillVoteSummary'),
      ),
    );

    $data['Hearing']['hid_video'] = array(
      'title' => t('Hearing Video hid'),
      'help' => t('Hearing Video hid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Video',
        'base field' => 'hid',
        'relationship field' => 'hid',
        'id' => 'standard',
        'label' => t('Hearing Video'),
      ),
    );

    $data['Hearing']['hid_committeehearings'] = array(
      'title' => t('CommitteeHearings hid'),
      'help' => t('CommitteeHearings hid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'CommitteeHearings',
        'base field' => 'hid',
        'relationship field' => 'hid',
        'id' => 'standard',
        'label' => t('CommitteeHearings Hearing'),
      ),
    );
    $data['CommitteeHearings']['cid'] = array(
      'title' => t('CommitteeHearings CID Field'),
      'help' => t('CommitteeHearings CID'),
      'field' => array(
        'id' => 'numeric',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Committee',
        'base field' => 'cid',
        'id' => 'standard',
        'label' => 'CommitteeHearings Committee',
      ),
    );

    $data['CommitteeHearings']['hid'] = array(
      'title' => t('CommitteeHearings HID Field'),
      'help' => t('CommitteeHearings HID.'),
      'field' => array(
        'id' => 'numeric',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Hearing',
        'base field' => 'hid',
        'id' => 'standard',
        'label' => 'CommitteeHearings Hearing',
      ),
    );
    return $data;
  }

}
