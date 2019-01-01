jQuery(document).ready(function () {
  twttr.ready(function (twttr) {
    twttr.events.bind('click', function (event) {
      Drupal.behaviors.campaigns.logCampaignMetric(
        drupalSettings.campaign_id, 'campaign_tweets');
    });
  });
});
