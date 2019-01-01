<?php

namespace Drupal\dd_hearing;

use Drupal\Core\Entity\EntityInterface;
use Drupal\dd_base\Entity\DdBaseStateFieldEntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Hearing entities.
 *
 * @ingroup dd_hearing
 */
class DdHearingListBuilder extends DdBaseStateFieldEntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Hearing ID');
    $header['date'] = $this->t('Date');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_hearing\Entity\DdHearing */
    $row['id'] = $this->l(
      $entity->id(),
      new Url(
        'entity.dd_hearing.canonical', array(
          'dd_hearing' => $entity->id(),
        )
      )
    );
    $row['date'] = $entity->getDate();
    return $row + parent::buildRow($entity);
  }

}
