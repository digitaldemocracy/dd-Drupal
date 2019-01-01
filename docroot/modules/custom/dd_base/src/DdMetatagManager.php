<?php

namespace Drupal\dd_base;

use Drupal\dd_video\Entity\DdVideo;
use \Drupal\metatag\MetatagManager;
use \Drupal\Core\Entity\ContentEntityInterface;

/**
 * Class DdMetatagManager
 *
 * Allows overriding of Metatags based on entity values.
 *
 * @package Drupal\dd_base
 */
class DdMetatagManager extends MetatagManager {

  /**
   * {@inheritdoc}
   */
  public function tagsFromEntity(ContentEntityInterface $entity) {
    $tags = parent::tagsFromEntity($entity);

    // Overrides from dd_hearing entity.
    if ($entity->getEntityTypeId() == 'dd_hearing') {
      $ids = DdVideo::getVideoFileIdsForHearingId($entity->id());
      $thumbnail = 'https://videostorage-us-west.s3.amazonaws.com/videos/' . $ids[0] . '/thumbnails/default.jpg';
      $tags['og_image'] = $thumbnail;
    }
    return $tags;
  }
}
