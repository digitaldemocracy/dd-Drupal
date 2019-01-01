<?php

namespace Drupal\dd_lobbyist;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Lobbyist entities.
 *
 * @ingroup dd_lobbyist
 */
class DdLobbyistListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('DD Lobbyist ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_lobbyist\Entity\DdLobbyist */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dd_lobbyist.edit_form', array(
          'dd_lobbyist' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
