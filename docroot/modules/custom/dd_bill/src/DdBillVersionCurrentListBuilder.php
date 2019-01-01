<?php

namespace Drupal\dd_bill;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Bill Version Current entities.
 *
 * @ingroup dd_bill
 */
class DdBillVersionCurrentListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Bill Version Current ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_bill\Entity\DdBillVersionCurrent */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_bill_version_current.canonical', array(
          'dd_bill_version_current' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
