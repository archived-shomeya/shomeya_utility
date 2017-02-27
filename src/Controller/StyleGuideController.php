<?php

namespace Drupal\shomeya_utility\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StyleGuideController.
 * @package Drupal\shomeya_utility\Controller
 */
class StyleGuideController extends ControllerBase {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface;
   */
  protected $formbuilder;

  /**
   * Build a new Style guide controller.
   */
  function __construct(FormBuilderInterface $formBuilder) {
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }


  /**
   * Return the Basic HTML elements.
   */
  public function htmlContent() {
    drupal_set_message($this->t('This is a status message!'), 'status');
    drupal_set_message($this->t('This is a warning message!'), 'warning');
    drupal_set_message($this->t('This is a error message!'), 'error');

    return ['#theme' => 'styleguide'];
  }

  /**
   * Return the basic form elements.
   */
  public function formContent() {
    $form_state = new FormState();
    $form = $this->formBuilder()->buildForm('\Drupal\shomeya_utility\Form\StyleGuideForm', $form_state);

    return $form;
  }

  /**
   * Return the basic render elements.
   */
  public function renderElementContent() {
    $build = [];

    $build['introduction'] = [
      '#markup' => $this->t('<p>This page demonstrates some of Drupal\'s standard HTML widgets, such as drop buttons, containers, pagers, etc.</p>')
    ];

    $build['dropbutton_intro'] = [
      '#markup' => $this->t('<h2>Dropbuttons</h2><p>This is a dropbutton often used in pages to present multiple choices, with a default recommended action.</p>')
    ];

    $build['dropbutton'] = [
      '#type' => 'dropbutton',
      '#links' => array(
        'edit' => array(
          'title' => $this->t('Edit'),
          'url' => Url::fromUserInput('#'),
        ),
        'delete' => array(
          'title' => $this->t('Delete'),
          'url' => Url::fromUserInput('#'),
        ),
        'clone' => array(
          'title' => $this->t('Clone'),
          'url' => Url::fromUserInput('#'),
        ),
        'inspect' => array(
          'title' => $this->t('Inspect'),
          'url' => Url::fromUserInput('#'),
        ),
      ),
    ];

    return $build;
  }
}
