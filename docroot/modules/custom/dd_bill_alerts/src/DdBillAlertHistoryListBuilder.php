<?php

namespace Drupal\dd_bill_alerts;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dd bill alert history entities.
 *
 * @ingroup dd_bill_alerts
 */
class DdBillAlertHistoryListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dd bill alert history ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_bill_alerts\Entity\DdBillAlertHistory */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_bill_alert_history.edit_form', array(
          'dd_bill_alert_history' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
