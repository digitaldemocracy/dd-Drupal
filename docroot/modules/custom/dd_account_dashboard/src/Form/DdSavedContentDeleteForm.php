<?php

namespace Drupal\dd_account_dashboard\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a form for deleting DD Saved Content entities.
 *
 * @ingroup dd_account_dashboard
 */
class DdSavedContentDeleteForm extends ContentEntityDeleteForm {
  private $path = '';
  private $urlAbsolute;
  private $urlDomain;
  private $queryArgs = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    parent::__construct($entity_manager);


    // Use referrer URL for bookmark URL, validate it.
    $referrer_url = \Drupal::request()->server->get('HTTP_REFERER');
    $fake_request = Request::create($referrer_url);
    $url_object = \Drupal::service('path.validator')->getUrlIfValid($fake_request->getRequestUri());
    if ($url_object) {
      $this->urlAbsolute = $referrer_url;
      $parsed_url = parse_url($this->urlAbsolute);
      $this->path = $parsed_url['path'];
      $this->urlDomain = $parsed_url['scheme'] . '://' . $parsed_url['host'];

      if ($parsed_url['query']) {
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
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    if ((strpos($this->path, '/user/') === FALSE) && (strpos($this->path, '/dd_saved_content/') === FALSE)) {
      $form['actions']['cancel']['#url'] = Url::fromUserInput($this->path, ['query' => $this->queryArgs]);
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    if ((strpos($this->path, '/user/') === FALSE) && (strpos($this->path, '/dd_saved_content/') === FALSE)) {
      $url = Url::fromUserInput($this->path, ['query' => $this->queryArgs]);
      $form_state->setRedirectUrl($url);
    }
  }
}
