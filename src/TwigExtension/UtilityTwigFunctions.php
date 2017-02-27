<?php

namespace Drupal\shomeya_utility\TwigExtension;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;

/**
 * Twig extension to add additional date formatting options
 * @package Drupal\shomeya_utility\TwigExtension
 */
class UtilityTwigFunctions extends \Twig_Extension {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;
  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'shomeya_utility_twig_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('url_from_uri', array($this, 'getUrlFromUri'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('image_style', array($this, 'getImageStyleUrl'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('time', array($this, 'timeElement'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('time_ago', array($this, 'timeAgo'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('time_from_string', array($this, 'timeFromString'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('time_ago_from_string', array($this, 'timeAgoFromString'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),
      new \Twig_SimpleFunction('date_range', array($this, 'dateRange'), array('is_safe_callback' => array($this, 'isUrlGenerationSafe'))),

    ];
  }

  /**
   * Turn a timestamp into a time element.
   *
   * @param $timestamp
   *   The timestamp to base the date element on.
   * @param $value
   *   (optional) The value to to be displayed in the element.
   * @param $options array
   *   (optional) The options to be passed to the element (and used for
   *   formatting the value, if value is omitted.
   *
   * @return array
   *   The time element to be rendered.
   */
  public function timeElement($timestamp, $value = NULL, array $options = []) {
    $options += [
      'type' => 'custom',
      'format' => 'j F Y',
      'timezone' => NULL,
    ];

    if (is_null($value)) {
      $value = $this->dateFormatter->format($timestamp, $options['type'], $options['format'], $options['timezone']);
    }

    $build = [
      '#type' => 'time',
      '#timestamp' => $timestamp,
      '#options' => $options,
      '#value' => $value
    ];

    return $build;
  }

  /**
   * Turn a timestamp into a time element formatted with jquery.timeago.js.
   *
   * @param $timestamp
   *   The timestamp to base the date element on.
   * @param $value
   *   (optional) The value to to be displayed in the element.
   * @param $options array
   *   (optional) The options to be passed to the element (and used for
   *   formatting the value, if value is omitted.
   *
   * @return array
   *   The time element to be rendered.
   */
  public function timeAgo($timestamp, $value = NULL, array $options = []) {
    $options += [
      'timeago' => TRUE,
    ];

    return $this->timeElement($timestamp, $value, $options);
  }

  /**
   * Turn a date string into a time element.
   *
   * @param $date_string
   *   The date string to base the date element on.
   * @param $value
   *   (optional) The value to to be displayed in the element.
   * @param $options array
   *   (optional) The options to be passed to the element (and used for
   *   formatting the value, if value is omitted.
   *
   * @return array
   *   The time element to be rendered.
   */
  public function timeFromString($date_string, $value = NULL, array $options = []) {
    $timestamp = strtotime($date_string);

    return $this->timeElement($timestamp, $value, $options);
  }

  /**
   * Turn a date string into a time element formatted with jquery.timeago.js.
   *
   * @param $date_string
   *   The date string to base the date element on.
   * @param $value
   *   (optional) The value to to be displayed in the element.
   * @param $options array
   *   (optional) The options to be passed to the element (and used for
   *   formatting the value, if value is omitted.
   *
   * @return array
   *   The time element to be rendered.
   */
  public function timeAgoFromString($date_string, $value = NULL, array $options = []) {
    $timestamp = strtotime($date_string);

    $options += [
      'timeago' => TRUE,
    ];

    return $this->timeElement($timestamp, $value, $options);
  }

  /**
   * Generates an absolute URL given a uri and parameters.
   *
   * @param $uri
   *   The uri.
   * @param array $options
   *   (optional) An associative array of additional options. The 'absolute'
   *   option is forced to be TRUE.
   *
   * @return string
   *   The generated absolute URL for the given uri.
   *
   * @todo Add an option for scheme-relative URLs.
   */
  public function getUrlFromUri($uri, $options = array()) {
    // Generate URL.
    $generated_url = Url::fromUri($uri, $options);

    // Return as render array, so we can bubble the bubbleable metadata.
    $build = ['#markup' => $generated_url->toString()];
    return $build;
  }

  /**
   * Generates a relative URL to an image style
   *
   * @param $uri
   *   The uri.
   * @param array $options
   *   (optional) An associative array of additional options. The 'absolute'
   *   option is forced to be TRUE.
   *
   * @return string
   *   The generated absolute URL for the given uri.
   *
   * @todo Add an option for scheme-relative URLs.
   */
  public function getImageStyleUrl($style, $uri) {
    $style = ImageStyle::load($style);

    if ($style) {
      $url = file_url_transform_relative(file_create_url($style->buildUrl($uri)));
    }
    else {
      $url = file_url_transform_relative(file_create_url($uri));
    }

    // Return as render array, so we can bubble the bubbleable metadata.
    $build = ['#markup' => $url];
    return $build;
  }

  /**
   * Sets the date formatter.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   *
   * @return $this
   */
  public function setDateFormatter(DateFormatterInterface $date_formatter) {
    $this->dateFormatter = $date_formatter;
    return $this;
  }

  /**
   * Turn a set of date strings into a date range.
   *
   * @param $timestamp
   *   The timestamp to base the date element on.
   * @param $value
   *   (optional) The value to to be displayed in the element.
   * @param $options array
   *   (optional) The options to be passed to the element (and used for
   *   formatting the value, if value is omitted.
   *
   * @return array
   *   The time element to be rendered.
   */
  public function dateRange($start, $end = NULL) {
    if ($start instanceof \Drupal\Core\Render\Markup) {
      $start = (string) $start;
    }
    if ($end instanceof \Drupal\Core\Render\Markup) {
      $end = (string) $end;
    }


    $start = strtotime($start);
    if ($end) {
      $end = strtotime($end);
    }

    if (empty($end)) {
      return $this->timeElement($start, NULL, ['type' => 'custom', 'format' => 'j F Y']);
    }

    if ($start == $end) {
      return $this->timeElement($start, NULL, ['type' => 'custom', 'format' => 'j F Y']);
    }

    if (date('M-j-Y', $start) == date('M-j-Y', $end)) {
      // close enough
      return $this->timeElement($start, NULL, ['type' => 'custom', 'format' => 'j F Y']);
    }


    // let's look at the YMD individually, and make a pretty string
    $dates = [
      's_year' => date('Y', $start),
      'e_year' => date('Y', $end),

      's_month' => date('F', $start),
      'e_month' => date('F', $end),

      's_day' => date('j', $start),
      'e_day' => date('j', $end),

    ];

    // init dates
    $start_date = '';
    $end_date = '';


    // Start by building the day
    $start_date .= $dates['s_day'];
    if ($dates['s_day'] != $dates['e_day']) {
      $end_date .= $dates['e_day'];
    }

    // Build the month
    if ($dates['s_month'] != $dates['e_month']) {
      $start_date .= ' '. $dates['s_month'];
    }
    $end_date .= ' '. $dates['e_month'];

    // Add year
    if ($dates['s_year'] != $dates['e_year']) {
      $start_date .= ' ' . $dates['s_year'];
    }
    $end_date .= ' ' . $dates['e_year'];

    $end_date_element = $this->timeElement($end, $end_date);
    $start_date_element = $this->timeElement($start, $start_date);

    $complete_date = [
      'start_date' => $start_date_element,
      'dash' => ['#markup' => ' &ndash; '],
      'end_date' => $end_date_element,
    ];

    return $complete_date;
  }


  /**
   * Determines at compile time whether the generated URL will be safe.
   *
   * Saves the unneeded automatic escaping for performance reasons.
   *
   * The URL generation process percent encodes non-alphanumeric characters.
   * Thus, the only character within an URL that must be escaped in HTML is the
   * ampersand ("&") which separates query params. Thus we cannot mark
   * the generated URL as always safe, but only when we are sure there won't be
   * multiple query params. This is the case when there are none or only one
   * constant parameter given. For instance, we know beforehand this will not
   * need to be escaped:
   * - path('route')
   * - path('route', {'param': 'value'})
   * But the following may need to be escaped:
   * - path('route', var)
   * - path('route', {'param': ['val1', 'val2'] }) // a sub-array
   * - path('route', {'param1': 'value1', 'param2': 'value2'})
   * If param1 and param2 reference placeholders in the route, it would not
   * need to be escaped, but we don't know that in advance.
   *
   * @param \Twig_Node $args_node
   *   The arguments of the path/url functions.
   *
   * @return array
   *   An array with the contexts the URL is safe
   */
  public function isUrlGenerationSafe(\Twig_Node $args_node) {
    // Support named arguments.
    $parameter_node = $args_node->hasNode('parameters') ? $args_node->getNode('parameters') : ($args_node->hasNode(1) ? $args_node->getNode(1) : NULL);

    if (!isset($parameter_node) || $parameter_node instanceof \Twig_Node_Expression_Array && count($parameter_node) <= 2 &&
      (!$parameter_node->hasNode(1) || $parameter_node->getNode(1) instanceof \Twig_Node_Expression_Constant)) {
      return array('html');
    }

    return array();
  }
}
