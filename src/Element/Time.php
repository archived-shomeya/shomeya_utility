<?php

/**
 * @file
 * Contains Time render element.
 */

namespace Drupal\shomeya_utility\Element;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Render\Markup;
use Drupal\Core\Template\Attribute;

/**
 * Provides a render element for HTML time, with properties and value.
 *
 * @RenderElement("time")
 */
class Time extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRenderAttributes'],
        [$class, 'preRenderTimeTag'],
      ],
      '#attributes' => [],
      '#datetime' => '',
      '#timestamp' => '',
      '#value' => NULL,
      '#options' => [
        'timeago' => FALSE,
      ],
    ];
  }

  public static function preRenderTimeTag($element) {
    $attributes = isset($element['#attributes']) ? new Attribute($element['#attributes']) : '';


    // An HTML tag should not contain any special characters. Escape them to
    // ensure this cannot be abused.
    $markup = '<time' . $attributes .'>';
    $markup .= SafeMarkup::isSafe($element['#value']) ? $element['#value'] : Xss::filterAdmin($element['#value']);
    $markup .= '</time'. ">\n";

    $element['#markup'] = Markup::create($markup);
    return $element;
  }

  public static function preRenderAttributes($element) {
    $attributes = isset($element['#attributes']) ? $element['#attributes'] : [];

    $attributes['datetime'] = \Drupal::getContainer()->get('date.formatter')->format($element['#timestamp'], 'custom', 'c');
    if (isset($element['#options']['timeago']) && $element['#options']['timeago']) {
      $attributes['data-drupal-time-ago'] = TRUE;
      $element['#attached'] = [
        'library' => [
          'shomeya_utility/drupal.shomeya_utility.time_ago',
        ],
      ];
    }

    $element['#attributes'] = $attributes;

    return $element;
  }
}
