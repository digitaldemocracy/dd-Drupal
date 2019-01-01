<?php

namespace Drupal\dd_committee;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Committee Authors entities.
 *
 * @ingroup dd_committee
 */
class DdCommitteeAuthorsListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Committee Authors ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_committee\Entity\DdCommitteeAuthors */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_committee_authors.edit_form', array(
          'dd_committee_authors' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
