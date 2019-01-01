<?php

namespace Drupal\dd_base;

abstract class BasicEnum {
  private static $constCacheArray = NULL;

  /**
   * BasicEnum constructor.
   */
  private function __construct() {
    // Prevent instance.
  }

  /**
   * Get Defined Constants.
   * @return mixed
   *   Constants
   */
  private static function getConstants() {
    if (self::$constCacheArray == NULL) {
      self::$constCacheArray = [];
    }

    $called_class = get_called_class();
    if (!array_key_exists($called_class, self::$constCacheArray)) {
      $reflect = new \ReflectionClass($called_class);
      self::$constCacheArray[$called_class] = $reflect->getConstants();
    }
    return self::$constCacheArray[$called_class];
  }

  /**
   * Is Constant Name Valid.
   *
   * @param string $name
   *   Constant name.
   * @param bool $strict
   *   If FALSE, ignores case.
   *
   * @return bool
   *   TRUE if valid, FALSE otherwise.
   */
  public static function isValidName($name, $strict = FALSE) {
    $constants = self::getConstants();

    if ($strict) {
      return array_key_exists($name, $constants);
    }

    $keys = array_map('strtolower', array_keys($constants));
    return in_array(strtolower($name), $keys);
  }

  /**
   * Is Constant Value Valid.
   *
   * @param mixed $value
   *   Value to check
   *
   * @return bool
   *   TRUE if valid, FALSE otherwise.
   */
  public static function isValidValue($value) {
    $values = array_values(self::getConstants());
    return in_array($value, $values, $strict = TRUE);
  }
}
