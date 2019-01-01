<?php

namespace Drupal\dd_payment_system;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Subscription Plan entities.
 *
 * @ingroup dd_payment_system
 */
class DdSubscriptionPlanListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Subscription Plan ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_payment_system\Entity\DdSubscriptionPlan */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_subscription_plan.edit_form', array(
          'dd_subscription_plan' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
