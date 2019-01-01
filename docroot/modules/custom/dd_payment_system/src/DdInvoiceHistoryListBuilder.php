<?php

namespace Drupal\dd_payment_system;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dd invoice history entities.
 *
 * @ingroup dd_payment_system
 */
class DdInvoiceHistoryListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dd invoice history ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_payment_system\Entity\DdInvoiceHistory */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_invoice_history.edit_form', array(
          'dd_invoice_history' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
