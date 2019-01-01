<?php

namespace Drupal\dd_metrics\Utility;

use Drupal\dd_base\BasicEnum;

/**
 * Class DdCampaignMetricTypes
 * @package Drupal\dd_metrics\Utility
 */
abstract class DdCampaignMetricTypes extends BasicEnum {
  // Campaign-wide metrics.
  const DD_METRICS_CAMPAIGN_VIEWS = 'campaign_views';
  const DD_METRICS_CAMPAIGN_VIDEO_PLAYS = 'campaign_video_plays';
  const DD_METRICS_CAMPAIGN_FB_SHARES = 'campaign_fb_shares';
  const DD_METRICS_CAMPAIGN_TWEETS = 'campaign_tweets';
  const DD_METRICS_CAMPAIGN_ADDRESS_FORM_SUBMITTED = 'campaign_address_form_submitted';
  const DD_METRICS_TAKE_ACTION_CLICKS = 'take_action_clicks';
  const DD_METRICS_BILL_VIEWS = 'bill_views';

  // Actions.
  const DD_METRICS_PHONE_LEGISLATOR_ACTION = 'phone_action';
  const DD_METRICS_EMAIL_LEGISLATOR_ACTION = 'email_action';
  const DD_METRICS_FAX_LEGISLATOR_ACTION = 'fax_action';
  const DD_METRICS_TWEET_LEGISLATOR_ACTION = 'tweet_legislator';
  const DD_METRICS_PHONE_GOVERNOR_ACTION = 'call_governor';
  const DD_METRICS_EMAIL_GOVERNOR_ACTION = 'email_governor';
  const DD_METRICS_FAX_GOVERNOR_ACTION = 'fax_governor';
  const DD_METRICS_DOWNLOAD_ACTION = 'download_action';
  const DD_METRICS_DISPLAY_VIDEO_ACTION = 'display_video_clip_montage';
  const DD_METRICS_LETTER_TO_EDITOR_ACTION = 'letter_to_editor';
}
