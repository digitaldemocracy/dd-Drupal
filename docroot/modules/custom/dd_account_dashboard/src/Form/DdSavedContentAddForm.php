<?php

namespace Drupal\dd_account_dashboard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\dd_account_dashboard\Entity\DdSavedContent;
use Drupal\dd_base\DdBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a form for adding DD Saved Content entities.
 *
 * @ingroup dd_account_dashboard
 */
class DdSavedContentAddForm extends FormBase {
  private $path;
  private $urlAbsolute;
  private $urlDomain;
  private $queryArgs = [];

  /**
   * {@inheritdoc}
   */
  public function __construct() {

    // Use referrer URL for bookmark URL, validate it.
    $referrer_url = \Drupal::request()->server->get('HTTP_REFERER');
    $fake_request = Request::create($referrer_url);
    $url_object = \Drupal::service('path.validator')->getUrlIfValid($fake_request->getRequestUri());
    if ($url_object) {
      $this->urlAbsolute = $referrer_url;
      $parsed_url = parse_url($this->urlAbsolute);
      $this->path = $parsed_url['path'];
      $this->urlDomain = $parsed_url['scheme'] . '://' . $parsed_url['host'];

      if (isset($parsed_url['query']) && !empty($parsed_url['query'])) {
        $params = explode('&', $parsed_url['query']);
        if ($params) {
          foreach ($params as $param) {
            list ($key, $value) = explode('=', $param);
            $this->queryArgs[$key] = $value;
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_saved_content_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $type = '') {
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#required' => TRUE,
    ];

    $form['restrict_end_date'] = [
      '#type' => 'checkbox',
      '#title' => t('Restrict date to before today'),
    ];

    $form['type'] = [
      '#type' => 'hidden',
      '#value' => $type,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $state = DdBase::getCurrentState();

    if ($form_state->getValue('restrict_end_date')) {
      $this->queryArgs['date_ts_1'] = date('Y-m-d');


    }

    $relative_url = Url::fromUserInput($this->path, ['query' => $this->queryArgs])->toString();
    $this->urlAbsolute = $this->urlDomain . $relative_url;
    $title = $form_state->getValue('type') . ' - ' . $form_state->getValue('description');

    try {
      $bookmark = DdSavedContent::create(
        array(
          'type' => 'dd_saved_content',
          'uid' => \Drupal::currentUser()->id(),
          'status' => 0,
          'title' => $title,
          'language' => 'und',
        ));
      $bookmark->field_description->setValue($form_state->getValue('description'));
      $bookmark->field_url->setValue($this->urlAbsolute);
      $bookmark->field_type->setValue($form_state->getValue('type'));
      $bookmark->field_state->setValue($state);
      $bookmark->field_path->setValue($relative_url);
      $bookmark->setCreatedTime(REQUEST_TIME);
      $bookmark->save();

      if ((strpos($this->path, '/user/') === FALSE) && (strpos($this->path, '/dd_saved_content/') === FALSE)) {
        $msg = $this->t('Added "@description" to <a href="/user/@uid/saved-content">My Saved Content</a>!',
          [
            '@uid' => \Drupal::currentUser()->id(),
            '@description' => $form_state->getValue('description')
          ]
        );
        drupal_set_message($msg);
        $url = Url::fromUserInput($this->path, ['query' => $this->queryArgs]);
        $form_state->setRedirectUrl($url);
      }
    }
    catch (\Exception $e) {
      drupal_set_message('Issue saving content!');
    }
  }
}
