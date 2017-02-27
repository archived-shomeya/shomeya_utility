/**
 * @file
 * Defines Javascript behaviors for the time element.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Behaviors for time elements
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Handles time ago for time elements.
   */
  Drupal.behaviors.shomeya_utility_time = {
    attach: function (context) {
      var $context = $(context);
      $context.find("time[data-drupal-time-ago]").timeago()

    }
  };

})(jQuery, Drupal, drupalSettings);
