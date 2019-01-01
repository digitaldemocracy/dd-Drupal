<?php

namespace Drupal\dd_clip;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Clip Bank Quota entities.
 *
 * @ingroup dd_clip
 */
class DdClipBankQuotaListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Clip Bank Quota ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_clip\Entity\DdClipBankQuota */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_clip_bank_quota.edit_form', array(
          'dd_clip_bank_quota' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
