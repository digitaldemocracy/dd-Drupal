<?php

namespace Drupal\dd_legislator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Legislative Staff entities.
 *
 * @ingroup dd_legislator
 */
class DdLegislativeStaffListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Legislative Staff ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_legislator\Entity\DdLegislativeStaff */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_legislative_staff.canonical', array(
          'dd_legislative_staff' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
