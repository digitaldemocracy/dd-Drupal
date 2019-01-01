<?php

namespace Drupal\dd_fax_service_payment;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dd fax service payment entity entities.
 *
 * @ingroup dd_fax_service_payment
 */
class DdFaxServicePaymentEntityListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dd fax service payment entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_fax_service_payment_entity.edit_form', [
          'dd_fax_service_payment_entity' => $entity->id(),
        ]
      )
    );
    return $row + parent::buildRow($entity);
  }

}
