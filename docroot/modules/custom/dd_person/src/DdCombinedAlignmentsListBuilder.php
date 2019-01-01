<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Combined Alignments entities.
 *
 * @ingroup dd_person
 */
class DdCombinedAlignmentsListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Combined Alignments ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_person\Entity\DdCombinedAlignments */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_combined_alignments.canonical', array(
          'dd_combined_alignments' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
