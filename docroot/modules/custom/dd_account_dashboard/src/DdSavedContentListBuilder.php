<?php

namespace Drupal\dd_account_dashboard;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Saved Content entities.
 *
 * @ingroup dd_account_dashboard
 */
class DdSavedContentListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Saved Content ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_account_dashboard\Entity\DdSavedContent */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_saved_content.edit_form', array(
          'dd_saved_content' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
