<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD General Public entities.
 *
 * @ingroup dd_person
 */
class DdGeneralPublicListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD General Public ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_person\Entity\DdGeneralPublic */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->getFullNameLastFirst(),
      new Url(
        'entity.dd_general_public.canonical', array(
          'dd_general_public' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
