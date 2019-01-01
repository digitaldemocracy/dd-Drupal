<?php

namespace Drupal\dd_account_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_account_dashboard\Entity\DdSavedContent;
use Drupal\dd_base\DdBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * DdSavedContentController controller.
 */
class DdSavedContentController extends ControllerBase {
  /**
   * Add bookmark nodes.
   */
  public function addBookmark() {
    $uid = \Drupal::currentUser()->id();
    if ($uid) {
      $state = DdBase::getCurrentState();

      $title = \Drupal::request()->attributes->get('title');
      $description = \Drupal::request()->attributes->get('description');
      $link = \Drupal::request()->attributes->get('link');
      $type = \Drupal::request()->attributes->get('type');
      try {
        $bookmark = DdSavedContent::create(
          array(
            'type' => 'dd_saved_content',
            'uid' => \Drupal::currentUser()->id(),
            'status' => 0,
            'title' => $title,
            'language' => 'und',
          ));
        $bookmark->field_description->setValue($description);
        $bookmark->field_url->setValue($link);
        $bookmark->field_type->setValue($type);
        $bookmark->field_state->setValue($state);
        $bookmark->setCreatedTime(REQUEST_TIME);
        $bookmark->save();
      }
      catch (\Exception $e) {
        return -1;
      }
      return new JsonResponse(['id' => $bookmark->id()]);
    }
  }
}
