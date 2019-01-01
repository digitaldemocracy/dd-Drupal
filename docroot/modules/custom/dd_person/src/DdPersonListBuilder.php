<?php

namespace Drupal\dd_person;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of DD Person entities.
 *
 * @ingroup dd_person
 */
class DdPersonListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Person ID');
    $header['last'] = $this->t('Last Name');
    $header['first'] = $this->t('First Name');
    $header['image'] = $this->t('Image URL');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dd_person\Entity\DdPerson */
    $row['id'] = $this->l(
      $entity->id(),
      new Url(
        'entity.dd_person.canonical', array(
          'dd_person' => $entity->id(),
        )
      )
    );
    $row['last'] = $entity->getLastName();
    $row['first'] = $entity->getFirstName();
    $row['image'] = $entity->getImage();

    return $row + parent::buildRow($entity);
  }

}
