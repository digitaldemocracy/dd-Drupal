<?php

namespace Drupal\dd_base;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the language manager service.
 */
class DdBaseServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides MetatagManager class for DD custom entities.
    if ($container->hasDefinition('metatag.manager')) {
      $definition = $container->getDefinition('metatag.manager');
      $definition->setClass('Drupal\dd_base\DdMetatagManager');
    }
  }
}
