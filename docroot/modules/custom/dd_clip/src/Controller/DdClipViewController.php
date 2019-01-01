<?php

namespace Drupal\dd_clip\Controller;

use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Url;
use Drupal\dd_clip\Entity\DdClip;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DdClipViewController extends EntityViewController {

  /**
   * View Clip by Video Id and view.
   *
   * @param string $videoid
   *   Video id to load.
   * @param int $campaign_id
   *   Campaign ID (optional).
   * @param int $action_id
   *   Paragraph Action Target ID (optional).
   *
   * @return mixed
   *   Rendered view.
   */
  public function viewClipByVideoId($videoid, $campaign_id = NULL, $action_id = NULL) {
    $field_values = [
      ['field' => 'field_videoid', 'value' => $videoid],
    ];
    $entities = DdClip::loadByFields($field_values);

    $user = \Drupal::currentUser();

    if (!empty($campaign_id)) {
      $campaign = Node::load($campaign_id);
      if ($campaign && $campaign->getType() == 'campaign') {
        // Check allowed to access campaign.
        if ($campaign->get('field_private_campaign')->value != 0 && !$user->id()) {
          throw new NotFoundHttpException();
        }
      }
      else {
        throw new NotFoundHttpException();
      }
    }

    if ($entities) {
      $entity = current($entities);
      if ($campaign_id) {
        // Set a campaign ID in clip.
        $entity->set('campaign_id', $campaign_id);

        if ($action_id) {
          // Set a action target ID in clip.
          $entity->set('action_id', $action_id);
        }
      }

      // Only show the clip if it is published or the user is the owner.
      if ($entity->isPublished() ||
          ($user && ($user->id() === $entity->getOwnerId() ||
           $user->hasPermission('view unpublished dd clip entities')))) {
        return $this->view($entity, empty($campaign_id) ? 'full' : 'video_only');
      }
    }
    throw new NotFoundHttpException();
  }

  /**
   * Edit Clip by Video Id and view.
   *
   * @param string $videoid
   *   Video id to load.
   *
   * @return mixed
   *   Rendered view.
   */
  public function editClipByVideoId($videoid) {
    $field_values = [
      ['field' => 'field_videoid', 'value' => $videoid],
    ];
    $entities = DdClip::loadByFields($field_values);
    if ($entities) {
      $entity = current($entities);
      $user = \Drupal::currentUser();
      // only show the clip if it is published or the user is the owner.
      if ($entity->isPublished() ||
        ($user && ($user->hasPermission('edit any dd clip entities') ||
         $user->id() === $entity->getOwnerId()))) {
        $url = Url::fromRoute('entity.dd_clip.edit_form',
                              ['dd_clip' => $entity->id()])->toString();
        return new RedirectResponse($url);
      }
    }
    throw new NotFoundHttpException();
  }

  /**
   * Delete Clip by Video Id and view.
   *
   * @param string $videoid
   *   Video id to load.
   *
   * @return mixed
   *   Rendered view.
   */
  public function deleteClipByVideoId($videoid) {
    $field_values = [
      ['field' => 'field_videoid', 'value' => $videoid],
    ];
    $entities = DdClip::loadByFields($field_values);
    if ($entities) {
      $entity = current($entities);
      $user = \Drupal::currentUser();
      // only show the clip if it is published or the user is the owner.
      if ($user && ($user->hasPermission('delete any dd clip entities') ||
          $user->id() === $entity->getOwnerId())) {
        $url = Url::fromRoute('entity.dd_clip.delete_form',
                              ['dd_clip' => $entity->id()])->toString();
        return new RedirectResponse($url);
      }
    }
    throw new NotFoundHttpException();
  }

  /**
   * View Clip by Id and view.
   * THis function is to override the path defined in DdClip class
   *
   * @param int $id
   *   id of the record
   *
   * @return mixed
   *   Rendered view.
   */
  public function viewClipById($id) {
    $entity = DdClip::load($id);
    if ($entity) {
      return $this->view($entity);
    }
    throw new NotFoundHttpException();
  }
}
