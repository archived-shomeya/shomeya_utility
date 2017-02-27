<?php

namespace Drupal\shomeya_utility;

/*
 * @file
 * Contains tools for accessing build or metadata.
 */
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * This class provides helpers for accessing metadata about the current codebase
 * generated from either git commands to extract information about the checkout
 * or by reading from a deploydata.json file in the level above the Drupal
 * docroot.
 */
class BuildMetadata {

  /**
   * The configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected static $configFactory;

  /**
   * Whether or not dpeloydata.sjon is present
   *
   * @var bool
   */
  protected static $_isBuild = FALSE;

  /**
   * Parsed data from deploydata.json
   *
   * @var array
   */
  protected static $_deployData = array();

  /**
   * Has deploydata been loaded yet.
   *
   * @var bool
   */
  protected static $_deployDataLoaded = FALSE;

  /**
   * Has commit info been loaded yet.
   *
   * @var bool
   */
  protected static $_commitInfoLoaded = FALSE;

  /**
   * Information about the latest commit.
   *
   * @var array
   */
  protected static $_commitInfo = array();

  /**
   * Load the build data from deploydata.json.
   *
   * @param null $path
   *   The path to search for the deploy data file.
   *
   * @return array
   *   The loaded deploy data or empty array.
   */
  protected static function loadDeployDataJson($path = NULL) {
    if ($path == NULL) {
      $path = dirname(DRUPAL_ROOT) . '/deploydata.json';
    }

    if (file_exists($path)) {
      self::$_isBuild = TRUE;
      self::$_deployData = (array) json_decode(file_get_contents($path));
    }

    self::$_deployDataLoaded = TRUE;
    return self::$_deployData;
  }

  /**
   * Whether or not the current site is a build.
   *
   * @return bool
   */
  public static function isBuild() {
    if (!self::$_deployDataLoaded) {
      self::loadDeployDataJson();
    }

    return self::$_isBuild;
  }

  /**
   * Get a key from deploy data, if present.
   *
   * @param $key string
   *   The key that you want to look up information for, such as 'build_date' or
   *   'branch' or 'buildhost'.
   *
   * @return string
   *   The request data or an empty string if not found.
   */
  protected static function _getBuildData($key) {
    $deploy_data = self::getDeployData();
    if (isset($deploy_data[$key])) {
      return $deploy_data[$key];
    }
    else {
      return '';
    }

  }

  /**
   * Get a key from cache, deploy data, or by parsing a git command.
   *
   * @param $key string
   *   The key that you want to look up information for, such as 'commit_sha' or
   *   'commit_author'.
   * @param $command string
   *   The git command to run to retrieve the data, such as
   *   'git log -1 --pretty=%s' or 'git rev-parse HEAD'.
   *
   * @return string
   *   The request data or an empty string if not found.
   */
  protected static function _gitParseDefault($key, $command = NULL) {
    if (!isset(self::$_commitInfo[$key])) {
      $deploy_data = self::getDeployData();
      if (isset($deploy_data[$key])) {
        self::$_commitInfo[$key] = $deploy_data[$key];
      }
      elseif ($command != NULL) {
        self::$_commitInfo[$key] = trim(shell_exec($command));
      }
      else {
        self::$_commitInfo[$key] = '';
      }
    }

    return self::$_commitInfo[$key];
  }

  /**
   * Load commit data.
   *
   * @return array
   */
  protected static function loadCommitData() {

    self::$_commitInfoLoaded = TRUE;
    return self::$_commitInfo;
  }

  /**
   * Return information about
   *
   * @return array
   */
  public static function getDeployData() {
    if (!self::$_deployDataLoaded) {
      self::loadDeployDataJson();
    }

    return self::$_deployData;
  }

  /**
   * Get the SHA1 hash/ID of the latest commit.
   *
   * @param string $format
   *   The format for the sha, either 'long' or 'short'
   *
   * @return
   *   The SHA for the current commit.
   */
  public static function getCommitSha($format = 'short') {
    $sha = self::_gitParseDefault('commit_sha', 'git rev-parse HEAD');

    if ($format == 'short') {
      return substr($sha, 0, 7);
    }
    else {
      return $sha;
    }
  }

  /**
   * Get the commit message of the latest commit
   *
   * @return string
   */
  public static function getCommitMessage() {
    return self::_gitParseDefault('commit_message', 'git log -1 --pretty=%s');
  }

  /**
   * Get the date of the latest commit.
   *
   * @return string
   */
  public static function getCommitDate() {
    return self::_gitParseDefault('commit_date', 'git log -1 --pretty=%ad');
  }

  /**
   * Get the author of the latest commit.
   *
   * @return string
   */
  public static function getCommitAuthor() {
    return self::_gitParseDefault('commit_author', 'git log -1 --pretty=%an');
  }

  /**
   * Get the branch of the build.
   *
   * @return string
   */
  public static function getBuildBranch() {
    return self::_gitParseDefault('branch', 'git rev-parse --abbrev-ref HEAD');
  }

  /**
   * Get the date of the build.
   *
   * @return string
   */
  public static function getBuildDate() {
    return self::_getBuildData('build_date');
  }

  /**
   * Get the name of the build host.
   *
   * @return string
   */
  public static function getBuildHost() {
    return self::_getBuildData('buildhost');
  }

  /**
   * Get the name of the user that created the build.
   *
   * @return string
   */
  public static function getBuildUser() {
    return self::_getBuildData('builduser');
  }

  /**
   * Get the commit SHA of the build.
   *
   * @return string
   */
  public static function getBuildCommitSha() {
    return self::_getBuildData('commit_sha');
  }

  /**
   * Get the repo for the build.
   *
   * @return string
   */
  public static function getBuildRepo() {
    return self::_getBuildData('repo');
  }

  /**
   * Get a URL that links to the commit.
   *
   * @return string
   *   The URL to view the commit info on the web.
   */
  public static function getCommitUrl() {
    $settings = self::getConfigFactory()->get('shomeya_utility.settings');
    $sha = self::getCommitSha('long');
    return $settings->get('github_url') . '/commit/' . $sha;
  }

  /**
   * Get a URL that links to the branch.
   *
   * @return string
   *   The URL to view the branch on the web.
   */
  public static function getBranchUrl() {
    $settings = self::getConfigFactory()->get('shomeya_utility.settings');
    $branch = self::getBuildBranch();
    return $settings->get('github_url') . '/tree/' . $branch;
  }

  /**
   * Get the configuration factory service.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   The configuration factory service.
   */
  public static function getConfigFactory() {
    if (!self::$configFactory) {
      self::$configFactory = \Drupal::configFactory();
    }

    return self::$configFactory;
  }

  /**
   * Set the configuration factory service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory service.
   */
  public static function setConfigFactory(ConfigFactoryInterface $configFactory) {
    self::$configFactory = $configFactory;
  }

}
