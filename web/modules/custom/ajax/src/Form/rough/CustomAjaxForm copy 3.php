<?php

namespace Drupal\ajax\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
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
                'wrapper' => 'edit-output-wrapper',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Verifying entry...'),
                ],
            ],
        ];

        $form['output'] = [
            '#type' => 'container',
            '#attributes' => ['id' => 'edit-output-wrapper'],
            'output_text' => [
                '#type' => 'textfield',
                '#size' => '60',
                '#disabled' => true,
                '#default_value' => 'Hello Drupal',
            ],
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
    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        $markup = 'nothing';
        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
            $markup = "<h2?>$selectedText</h2>";
}
$output = "<div id='edit-output-wrapper'>$markup</div>";

// Render the specific part of the form to update.
$response->addCommand(new ReplaceCommand('#edit-output-wrapper', $output));

return $response;
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