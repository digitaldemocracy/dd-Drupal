<?php

namespace Drupal\dd_alerts\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_committee\Entity\DdCommittee;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a 'DdShareToolbarBlock' block.
 *
 * @Block(
 *  id = "dd_share_toolbar_block",
 *  admin_label = @Translation("Share Toolbar Block"),
 * )
 */
class DdShareToolbarBlock extends BlockBase {
  const DD_TOOLBAR_VIDEO_ALERT_URL = '/node/add/dd_email_subscription';
  const DD_TOOLBAR_BILL_ALERT_URL = '/node/add/dd_bill_alert';

  private $enableVideoClip = FALSE;
  private $enableAlert = FALSE;
  private $enableEmail = FALSE;
  private $enableFacebook = FALSE;
  private $enableTwitter = FALSE;
  private $enableLinkedin = FALSE;
  private $enableBookmark = FALSE;
  private $videoClipUrl = '';
  private $alertUrl = '';
  private $email = '';
  private $emailUrl = '';
  private $fbShareUrl = '';
  private $twitterShareUrl = '';
  private $linkedinShareUrl = '';

  /**
   * Get Enable Video Clip.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableVideoClip() {
    return $this->enableVideoClip;
  }

  /**
   * Set Enable Video Clip.
   *
   * @param bool $val
   *   Enable TRUE/FALSE
   */
  public function setEnableVideoClip($val) {
    $this->enableVideoClip = $val;
  }

  /**
   * Get Enable Alert.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableAlert() {
    return $this->enableAlert;
  }

  /**
   * Set Enable Alert.
   *
   * @param bool $val
   *   Enable Alert TRUE/FALSE
   */
  public function setEnableAlert($val) {
    $this->enableAlert = $val;
  }

  /**
   * Get Enable Email.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableEmail() {
    return $this->enableEmail;
  }

  /**
   * Set Enable Email.
   *
   * @param bool $val
   *   Enable Email TRUE/FALSE
   */
  public function setEnableEmail($val) {
    $this->enableEmail = $val;
  }

  /**
   * Get Enable Facebook.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableFacebook() {
    return $this->enableFacebook;
  }

  /**
   * Set Enable Facebook.
   *
   * @param bool $val
   *   Enable Facebook TRUE/FALSE
   */
  public function setEnableFacebook($val) {
    $this->enableFacebook = $val;
  }

  /**
   * Get Enable Twitter.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableTwitter() {
    return $this->enableTwitter;
  }

  /**
   * Set Enable Twitter.
   *
   * @param bool $val
   *   Enable Twitter TRUE/FALSE
   */
  public function setEnableTwitter($val) {
    $this->enableTwitter = $val;
  }
  /**
   * Get Enable Linkedin.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableLinkedin() {
    return $this->enableLinkedin;
  }

  /**
   * Set Enable Linkedin.
   *
   * @param bool $val
   *   Enable Linkedin TRUE/FALSE
   */
  public function setEnableLinkedin($val) {
    $this->enableLinkedin = $val;
  }

  /**
   * Get Enable Bookmark.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function getEnableBookmark() {
    return $this->enableBookmark;
  }

  /**
   * Set Enable Bookmark.
   *
   * @param bool $val
   *   Enable Bookmark TRUE/FALSE
   */
  public function setEnableBookmark($val) {
    $this->enableBookmark = $val;
  }

  /**
   * Get Video Clip URL.
   *
   * @return string
   *   Video Clip URL
   */
  public function getVideoClipUrl() {
    return $this->videoClipUrl;
  }

  /**
   * Set Video Clip URL.
   *
   * @param string $video_clip_url
   *   Video Clip URL.
   */
  public function setVideoClipUrl($video_clip_url) {
    $this->videoClipUrl = $video_clip_url;
  }

  /**
   * Get Alert URL.
   *
   * @return string
   *   Alert URL
   */
  public function getAlertUrl() {
    return $this->alertUrl;
  }

  /**
   * Set Alert URL.
   *
   * @param string $alert_url
   *   Alert link URL.
   */
  public function setAlertUrl($alert_url) {
    $this->alertUrl = $alert_url;
  }

  /**
   * Get Email.
   *
   * @return string
   *   Email address.
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set Email.
   *
   * @param string $email
   *   Email Address
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * Get Email URL.
   *
   * @return string
   *   Email URL
   */
  public function getEmailUrl() {
    return $this->emailUrl;
  }

  /**
   * Set Email URL.
   *
   * @param string $email_url
   *   Email link URL.
   */
  public function setEmailUrl($email_url) {
    $this->emailUrl = $email_url;
  }

  /**
   * Get Facebook Share URL.
   *
   * @return string
   *   Facebook Share URL
   */
  public function getFbShareUrl() {
    return $this->fbShareUrl;
  }

  /**
   * Set Facebook Share URL.
   *
   * @param string $fb_share_url
   *   Facebook Share URL
   */
  public function setFbShareUrl($fb_share_url) {
    $this->fbShareUrl = $fb_share_url;
  }

  /**
   * Get Twitter Share URL.
   *
   * @return string
   *   Twitter Share URL
   */
  public function getTwitterShareUrl() {
    return $this->twitterShareUrl;
  }

  /**
   * Set Twitter Share URL.
   *
   * @param string $twitter_share_url
   *   Twitter Share URL
   */
  public function setTwitterShareUrl($twitter_share_url) {
    $this->twitterShareUrl = $twitter_share_url;
  }

  /**
   * Get LinkedIn Share URL.
   *
   * @return string
   *   LinkedIn Share URL
   */
  public function getLinkedinShareUrl() {
    return $this->linkedinShareUrl;
  }

  /**
   * Set LinkedIn Share URL.
   *
   * @param string $linkedin_share_url
   *   LinkedIn Share URL
   */
  public function setLinkedinShareUrl($linkedin_share_url) {
    $this->linkedinShareUrl = $linkedin_share_url;
  }

  /**
   * Get Bookmark URL.
   *
   * @param string $type
   *   Type of bookmark (bill, committee, etc).
   *
   * @return string
   *   Bookmark URL
   */
  public static function getBookmarkLink($type) {
    // Check if path is bookmarked.
    $path = \Drupal::service('path.current')->getPath();
    $query = \Drupal::request()->query;
    $path = Url::fromUserInput($path, ['query' => $query->all()])->toString();
    $uid = \Drupal::currentUser()->id();

    $is_saved_class = '';
    $title = 'Save to My Account';
    $url = Url::fromRoute('dd_account_dashboard.add_bookmark', ['type' => $type]);

    if ($uid && $path != '') {
      $saved_content = \Drupal::entityQuery('dd_saved_content')
        ->condition('field_path', $path)
        ->condition('user_id', $uid)
        ->execute();
      if ($saved_content) {
        $url = Url::fromRoute('entity.dd_saved_content.delete_form', ['dd_saved_content' => current($saved_content)]);
        $is_saved_class = 'is-saved';
        $title = 'Remove from My Account';
      }
      $bookmark_link = [
        '#type' => 'link',
        '#url' => $url,
        '#attributes' => [
          'class' => ['use-ajax', 'dd-share-toolbar-group-item'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 400,
          ]),
          'title' => $title,
        ],
        '#title' => t('<i class="fa fa-floppy-o fa-2x fa-fw @is-saved-class" aria-label="Save to My Account"></i>', ['@is-saved-class' => $is_saved_class]),
      ];
    }
    else {
      $options['query'] = ['destination' => $path];
      $url = Url::fromRoute('user.login', [], $options);

      $bookmark_link = [
        '#type' => 'link',
        '#url' => $url,
        '#attributes' => [
          'class' => ['dd-share-toolbar-group-item'],
          'title' => 'Log in to save content',
        ],
        '#title' => t('<i class="fa fa-floppy-o fa-2x fa-fw @is-saved-class" aria-label="Log in to save content"></i>', ['@is-saved-class' => $is_saved_class]),
      ];
    }
    return $bookmark_link;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $base_url = $GLOBALS['base_url'];
    $current_uri = $GLOBALS['base_url'] . \Drupal::request()->getRequestUri();

    $source = '';
    $parameters = \Drupal::routeMatch()->getParameters();
    $keys = $parameters->keys();
    $source_mapping = [
      'cn_id' => 'committee',
      'dd_committee' => 'committee',
      'dd_bill_bid' => 'bill',
      'dd_hearing' => 'hearing',
      'dd_person' => 'person',
    ];

    if ($keys && in_array($keys[0],array_keys($source_mapping))) {
      $source = $source_mapping[$keys[0]];
    }

    // Don't continue if we don't know the source.
    if ($source == '') {
      return NULL;
    }

    // Set a uid of 0 if not logged in.
    $uid = \Drupal::currentUser()->id() ? \Drupal::currentUser()->id() : 0;

    switch ($source) {
      case 'committee':
        $cn_id = $parameters->get('cn_id');
        $committee = DdCommittee::loadCommitteeByNameId($cn_id);
        $email = $committee->getEmail();
        if ($email != '') {
          $this->setEmail($email);
          $this->setEnableEmail(TRUE);
          $this->setEmailUrl('mailto:' . $this->getEmail());
        }

        $this->setAlertUrl(self::DD_TOOLBAR_VIDEO_ALERT_URL . '?committee_name=' . urlencode($committee->getName()) . '&cn_id=' . $cn_id);
        $this->setEnableAlert(TRUE);
        $this->setEnableFacebook(TRUE);
        $this->setEnableTwitter(TRUE);
        $this->setEnableLinkedin(TRUE);
        $this->setEnableBookmark(TRUE);

        break;

      case 'bill':
        $this->setEnableAlert(TRUE);
        $this->setEnableFacebook(TRUE);
        $this->setEnableTwitter(TRUE);
        $this->setEnableLinkedin(TRUE);
        $this->setEnableBookmark(TRUE);

        $bid = $parameters->get('dd_bill_bid');
        $bills = DdBill::loadByFields([['field' => 'bid', 'value' => $bid]]);
        if ($bills) {
          $bill = current($bills);
          $this->setAlertUrl(self::DD_TOOLBAR_BILL_ALERT_URL . '?bill_type=' . $bill->getType() . '&bill_number=' . $bill->getNumber());
        }
        else {
          $this->setAlertUrl(self::DD_TOOLBAR_BILL_ALERT_URL);
        }
        break;

      case 'hearing':
        $this->setEnableVideoClip(TRUE);
        $this->setEnableFacebook(TRUE);
        $this->setEnableTwitter(TRUE);
        $this->setEnableLinkedin(TRUE);
        $this->setEnableBookmark(TRUE);

        $hid = $parameters->get('dd_hearing')->id();
        //$start_time = \Drupal::request()->query->get('startTime');
        $vid = \Drupal::request()->query->get('vid');
        //$this->setVideoClipUrl($base_url . '/user/' . $uid . '/dd_video_editor/video_clipper/hid=' . $hid . '&startTime=' . $start_time . '&vid=' . $vid);
        $this->setVideoClipUrl($base_url . '/user/' . $uid . '/dd_video_editor/video_clipper/hid=' . $hid);
        break;

      case 'person':
        $pid = $parameters->get('dd_person')->id();
        $this->setAlertUrl(self::DD_TOOLBAR_VIDEO_ALERT_URL . '?speaker_pid=' . $pid);
        $this->setEnableAlert(TRUE);
        $this->setEnableFacebook(TRUE);
        $this->setEnableTwitter(TRUE);
        $this->setEnableLinkedin(TRUE);
        $this->setEnableBookmark(TRUE);

        // @todo Replace this with actual URL for email endpoint.
        //$this->setEmailUrl('https://digitaldemocracy.org/person/' . $pid);
        //$this->setEnableEmail(TRUE);
        break;
    }

    // @todo Change once bookmarking implemented.
    $this->setFbShareUrl('https://www.facebook.com/sharer/sharer.php?u=' . urlencode($current_uri));
    $this->setTwitterShareUrl('https://twitter.com/intent/tweet?url=' . urlencode($current_uri));
    $this->setLinkedinShareUrl('https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($current_uri));

    $render = array(
      '#theme' => 'dd_share_toolbar',
      '#cache' => array(
        'max-age' => 0,
      ),
      '#enable_video_clip' => $this->getEnableVideoClip(),
      '#enable_alert' => $this->getEnableAlert(),
      '#enable_email' => $this->getEnableEmail(),
      '#enable_facebook' => $this->getEnableFacebook(),
      '#enable_twitter' => $this->getEnableTwitter(),
      '#enable_linkedin' => $this->getEnableLinkedin(),
      '#enable_bookmark' => $this->getEnableBookmark(),
      '#video_clip_url' => $this->getVideoClipUrl(),
      '#alert_url' => $this->getAlertUrl(),
      '#email_url' => $this->getEmailUrl(),
      '#fb_share_url' => $this->getFbShareUrl(),
      '#twitter_share_url' => $this->getTwitterShareUrl(),
      '#linkedin_share_url' => $this->getLinkedinShareUrl(),
      '#bookmark_link' => self::getBookmarkLink($source),
    );
    return $render;
  }
}
