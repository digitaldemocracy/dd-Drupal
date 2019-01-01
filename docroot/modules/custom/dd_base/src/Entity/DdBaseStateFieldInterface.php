<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining DD Base State entities.
 *
 * @ingroup dd_base
 */
interface DdBaseStateFieldInterface extends ContentEntityInterface {
  /**
   * Gets the DD Base state.
   *
   * @return string
   *   State of the DD Base.
   */
  public function getState();

  /**
   * Sets the DD Base state.
   *
   * @param string $state
   *   The DD Base state.
   *
   * @return \Drupal\dd_base\Entity\DdBaseStateField
   *   The called DD Committee entity.
   */
  public function setState($state);
}
