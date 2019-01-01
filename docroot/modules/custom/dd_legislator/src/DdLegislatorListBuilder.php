<?php

namespace Drupal\dd_legislator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;
use Drupal\dd_base\Entity\DdBaseStateFieldEntityListBuilder;

/**
 * Defines a class to build a listing of DD Legislator entities.
 *
 * @ingroup dd_legislator
 */
class DdLegislatorListBuilder extends DdBaseStateFieldEntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Legislator ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_legislator\Entity\DdLegislator */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->getLastName() . ', ' . $entity->getFirstName(),
      new Url(
        'entity.dd_legislator.canonical', array(
          'dd_legislator' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
