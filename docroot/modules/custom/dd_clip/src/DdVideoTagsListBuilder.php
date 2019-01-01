<?php

namespace Drupal\dd_clip;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Video Tags entities.
 *
 * @ingroup dd_clip
 */
class DdVideoTagsListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Video Tags ID');
    $header['tag'] = $this->t('Tag');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_clip\Entity\DdVideoTags */
    $row['id'] = $entity->id();
    $row['tag'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_video_tags.edit_form', array(
          'dd_video_tags' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
