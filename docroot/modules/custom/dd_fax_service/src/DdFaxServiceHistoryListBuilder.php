<?php

namespace Drupal\dd_fax_service;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dd fax service history entities.
 *
 * @ingroup dd_fax_service
 */
class DdFaxServiceHistoryListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dd fax service history ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_fax_service\Entity\DdFaxServiceHistory */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_fax_service_history.edit_form', array(
          'dd_fax_service_history' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
