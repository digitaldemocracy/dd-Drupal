<?php

namespace Drupal\dd_bill;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Bill Discussion entities.
 *
 * @ingroup dd_bill
 */
class DdBillDiscussionListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Bill Discussion ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_bill\Entity\DdBillDiscussion */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_bill_discussion.canonical', array(
          'dd_bill_discussion' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
