<?php


namespace Drupal\shomeya_utility\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StyleGuideForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['intro_text'] = [
      '#markup' => $this->t('<p>This page demonstrate elements commonly found in Drupal forms.</p>'),
    ];

    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#required' => TRUE,
      '#placeholder' => $this->t('This is placeholder text'),
    ];

    $form['text_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text with Description'),
      '#description' => $this->t('Information or instructions for this form item.'),
    ];

    $form['text_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text with field prefix'),
      '#field_prefix' => $this->t('$'),
    ];

    $form['number_suffix'] = [
      '#type' => 'number',
      '#title' => $this->t('Number with field suffix'),
      '#field_suffix' => $this->t('units'),
    ];

    $form['search'] = [
      '#type' => 'search',
      '#title' => $this->t('Search'),
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Tel'),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    $form['multiemail'] = [
      '#type' => 'email',
      '#title' => $this->t('Multiple Emails'),
      '#attributes' => ['multiple' => 'true']
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
    ];

    $form['password_confirm'] = [
      '#type' => 'password_confirm',
      '#title' => $this->t('Password Confirmation'),
    ];

    $form['datetime'] = [
      '#type' => 'datetime',
      '#title' => $this->t('DateTime'),
    ];

    $form['expiration'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
    ];

    $form['number'] = [
      '#type' => 'number',
      '#title' => $this->t('Number'),
    ];

    $form['range'] = [
      '#type' => 'range',
      '#min' => 0,
      '#max' => 10,
      '#step' => 2,
      '#title' => $this->t('Range'),
    ];

    $form['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color'),
      '#default_value' => '#2f2f6c',
    ];

    $form['checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('This is a single checkbox'),
    ];

    $form['checkboxes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Checkboxes'),
      '#options' => ['alpha' => $this->t('Alpha'), 'beta' => $this->t('Beta'), 'gamma' => $this->t('Gamma')],
      '#default_value' => ['alpha']
    ];

    $form['radio'] = [
      '#type' => 'radio',
      '#title' => $this->t('This is a single radio'),
    ];

    $form['radios'] = [
      '#type' => 'radios',
      '#title' => $this->t('Radios'),
      '#options' => ['alpha' => $this->t('Alpha'), 'beta' => $this->t('Beta'), 'gamma' => $this->t('Gamma')],
      '#default_value' => 'alpha'
    ];

    $form['file'] = [
      '#type' => 'file',
      '#title' => $this->t('File'),
    ];

    $form['multifile'] = [
      '#type' => 'file',
      '#multiple' => TRUE,
      '#title' => $this->t('Multiple Files'),
    ];

    $form['select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select'),
      '#options' => [
        $this->t('Option Group') => [
          'alpha' => $this->t('Alpha'),
          'beta' => $this->t('Beta'),
          'gamma' => $this->t('Gamma'),
          'long' => $this->t('Really lengthy option for this select'),
        ],
        $this->t('Option Group 2') => [
          'zeta' => $this->t('Zeta'),
          'omega' => $this->t('Omega'),
        ],
      ],
    ];

    $form['multiselect'] = [
      '#type' => 'select',
      '#title' => $this->t('Multiple Select'),
      '#multiple' => TRUE,
      '#options' => [
        $this->t('Option Group') => [
          'alpha' => $this->t('Alpha'),
          'beta' => $this->t('Beta'),
          'gamma' => $this->t('Gamma'),
          'long' => $this->t('Really lengthy option for this select'),
        ],
        $this->t('Option Group 2') => [
          'zeta' => $this->t('Zeta'),
          'omega' => $this->t('Omega'),
        ],
      ],
    ];

    $form['textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Textarea'),
    ];

    $form['button'] = [
      '#type' => 'button',
      '#value' => $this->t('Button'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['language_configuration'] = [
      '#type' => 'language_configuration',
      '#title' => $this->t('Language Configuration')
    ];

    $form['language_select'] = [
      '#type' => 'language_select',
      '#title' => $this->t('Language Select')
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#default_value' => 0,
      '#delta' => 10,
    ];

    $form['formatted_text_restricted'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Formatted Text (Restricted HTML)'),
      '#format' => 'restricted_html',
      '#default_value' => $this->t('<p>The quick brown fox jumped over the lazy dog.</p>'),
    );

    $form['formatted_text_basic'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Formatted Text (Basic HTML)'),
      '#format' => 'basic_html',
      '#default_value' => $this->t('<p>The quick brown fox jumped over the lazy dog.</p>'),
    );

    $form['formatted_text_full'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Formatted Text (Full HTML)'),
      '#format' => 'full_html',
      '#default_value' => $this->t('<p>The quick brown fox jumped over the lazy dog.</p>'),
    );

    $form['table'] = [
      '#type' => 'table',
      '#header' => [
        '',
        $this->t('Enterprise'),
        $this->t('Enterprise D'),
        $this->t('Galactica'),
      ],
      '#rows' => [
        [
          $this->t('Captain'),
          $this->t('James Kirk'),
          $this->t('Jean-Luc Picard'),
          $this->t('William Adama'),
        ],
        [
          $this->t('XO'),
          $this->t('Spock'),
          $this->t('William Riker'),
          $this->t('Saul Tigh'),
        ],
        [
          $this->t('Doctor'),
          $this->t('Leonard McCoy'),
          $this->t('Beverly Crusher'),
          $this->t('Doc Cottle'),
        ],
        [
          $this->t('Engineer'),
          $this->t('Montgomery Scott'),
          $this->t('Geordi LaForge'),
          $this->t('Galen Tyrol'),
        ],
      ],
    ];

    $form['tableselect'] = [
      '#type' => 'tableselect',
      '#header' => [
        $this->t('Series'),
        $this->t('Years'),
        $this->t('Episodes'),
        $this->t('Seasons'),
      ],
      '#options' => [
        'tos' => [
          $this->t('The Original Series'),
          $this->t('1966-1969'),
          $this->t('79'),
          $this->t('3')
        ],
        'tas' => [
          $this->t('Star Trek: The Animated Series'),
          $this->t('1973-1974'),
          $this->t('22'),
          $this->t('2')
        ],
        'tng' => [
          $this->t('Star Trek: The Next Generation'),
          $this->t('1987-1994'),
          $this->t('176'),
          $this->t('7')
        ],
        'ds9' => [
          $this->t('Star Trek: Deep Space Nine'),
          $this->t('1993-1999'),
          $this->t('176'),
          $this->t('7')
        ],
        'voy' => [
          $this->t('Star Trek: Voyager'),
          $this->t('1995-2001'),
          $this->t('172'),
          $this->t('7')
        ],
        'ent' => [
          $this->t('Star Trek: Enterprise'),
          $this->t('2001-2005'),
          $this->t('98'),
          $this->t('4')
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'shomeya_utility_style_guide_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}
