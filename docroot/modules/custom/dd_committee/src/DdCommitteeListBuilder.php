<?php

namespace Drupal\dd_committee;

use Drupal\Core\Entity\EntityInterface;
use Drupal\dd_base\Entity\DdBaseStateFieldEntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Committee entities.
 *
 * @ingroup dd_committee
 */
class DdCommitteeListBuilder extends DdBaseStateFieldEntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Committee ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_committee\Entity\DdCommittee */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_committee.canonical', array(
          'dd_committee' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
