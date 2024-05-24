<?php

namespace Drupal\ajax\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a default form.
 */
class CustomAjaxForm extends FormBase
{

/**
 * {@inheritdoc}
 */
    public function getFormId()
    {
        return 'Custom_form';
    }

/**
 * {@inheritdoc}
 */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['example_select'] = [
            '#type' => 'select',
            '#title' => $this->t('Select Element'),
            '#options' => [
                '1' => 'one',
                '2' => 'two',
                '3' => 'three',
                '4' => 'four',
            ],
            '#ajax' => [
                'callback' => '::myAjaxCallback',
                'event' => 'change',
                'wrapper' => 'edit-output',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Verifying entry...'),
                ],
            ],
        ];

        $form['output'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#disabled' => true,
            '#default_value' => "Hello Drupal ",
            '#prefix' => '<div id="edit-output">',
            '#suffix' => '</div>',
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return $form;
    }

/**
 * AJAX callback handler for the select element.
 */
    public function myAjaxCallback0(array &$form, FormStateInterface $form_state)
    {
        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
            $form['output']['#value'] = $selectedText;
        }

        return $form['output'];
    }

    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {

        $markup = 'nothing selected';
        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
            $markup = "<h1>$selectedText</h1>";
        }
        $output = "<div id='edit-output'>$markup</div>";
        return ['#markup' => $output];
    }

/**
 * {@inheritdoc}
 */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }

/**
 * {@inheritdoc}
 */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
// Display result.
        foreach ($form_state->getValues() as $key => $value) {
            \Drupal::messenger()->addStatus($key . ': ' . $value);
        }
    }
}