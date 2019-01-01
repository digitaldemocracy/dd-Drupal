<?php

namespace Drupal\dd_utterance;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Utterance entities.
 *
 * @ingroup dd_utterance
 */
class DdUtteranceListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Utterance ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_utterance\Entity\DdUtterance */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_utterance.canonical', array(
          'dd_utterance' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
