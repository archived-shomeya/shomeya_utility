<?php

/**
 * @file
 * Functionality related to environment indicators.
 */

namespace Drupal\shomeya_utility;

use Drupal\Core\Site\Settings;

class Environment {

  const WARN = 'shonmeya-utility-warn';

  const ERROR = 'shomeya-utility-error';

  const OK = 'shomeya-utility-ok';

  /**
   * Map of environment labels.
   *
   * @var array
   */
  protected static $labelMap = [
    'none' => 'Unknown',
    'local' => 'Localhost',
    'dev' => 'Development',
    'development' => 'Development',
    'stage' => 'Staging',
    'staging' => 'Staging',
    'prod' => 'Production',
    'production' => 'Production',
  ];

  /**
   * Map of environment levels.
   *
   * @var array
   */
  protected static $levelMap = [
    'none' => self::WARN,
    'local' => self::ERROR,
    'dev' => self::ERROR,
    'development' => self::ERROR,
    'stage' => self::ERROR,
    'staging' => self::ERROR,
    'prod' => self::OK,
    'production' => self::OK,
  ];

  /**
   * Whether or not the environment is initialized.
   *
   * @var bool
   */
  protected static $intialized = FALSE;

  /**
   * The environment string.
   *
   * @var string
   */
  protected static $environment;

  /**
   * Get the label for the environment.
   *
   * @return string
   */
  public static function getLabel() {
    if (!self::$intialized) {
      self::intialize();
    }

    return self::getEnvironmentLabel(self::$environment);
  }

  /**
   * Get the environment status.
   *
   * @return string
   *   The string for the status.
   */
  public static function getStatusClass() {
    if (!self::$intialized) {
      self::intialize();
    }

    return self::getEnvironmentStatus(self::$environment);
  }

  /**
   * Initialize the environment from Settings.
   */
  protected static function intialize() {
    self::$intialized = TRUE;
    $env = Settings::get('DRUPAL_ENV');

    if (!empty($env)) {
      self::$environment = $env;
    }
    else {
      self::$environment = 'none';
    }
  }

  /**
   * Get the label for an environment from it's machine name.
   *
   * @param string $environment
   *   The machine name for the environment.
   */
  protected static function getEnvironmentLabel($environment) {
    if (isset(self::$labelMap[$environment])) {
      return self::$labelMap[$environment];
    }
    else {
      return self::$labelMap['none'];
    }
  }

  /**
   * Get the label for an environment from it's machine name.
   *
   * @param string $environment
   *   The machine name for the environment.
   */
  protected static function getEnvironmentStatus($environment) {
    if (isset(self::$levelMap[$environment])) {
      return self::$levelMap[$environment];
    }
    else {
      return self::$levelMap['none'];
    }
  }
}
