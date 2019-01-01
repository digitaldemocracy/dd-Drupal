(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.campaigns = {
    logCampaignMetric: function (campaign_id, metric_type) {
      $.ajax({
        url: '/log-campaign-metric/' + campaign_id + '/' + metric_type,
        success: function (data, status, xhr) {
        }
      });
    },
    logActionMetric :function (campaign_id, action_id, campaign_action_id = 0, campaign_action_paragraphs_id = 0, target_pid = 0, action_conversion = 0) {
      if (target_pid === '') {
        target_pid = 0;
      }
      if (campaign_action_paragraphs_id === '') {
        campaign_action_paragraphs_id = 0;
      }

      if (action_conversion === '') {
        action_conversion = 0;
      }
      $.ajax({
        url: '/log-action-metric/' + campaign_id + '/' + action_id + '/' + campaign_action_id + '/' + campaign_action_paragraphs_id + '/' + target_pid + '/' + action_conversion,
        success: function (data, status, xhr) {
        }
      });
    },
    attach: function (context, settings) {
    }
  };
})(Drupal, jQuery);
