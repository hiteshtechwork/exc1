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
                // 'callback' => [$this, 'myAjaxCallback'],

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
            '#prefix' => '<div id="edit-output',
            '#suffix' => '</div>',
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('save'),
        ];

        return $form;
    }

    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {
        $selectedText = 'nothing selected';
        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
        }
        // Attach the javascript library for the dialog box command
        // in the same way you would attach your custom JS scripts.
        $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
        $dialogText['#markup'] = "You selected : $selectedText";

        // If we want to execute AJAX commands our callback needs to return
        // an AjaxResponse object. let's create it and add our commands.

        $response = new AjaxResponse();
        // Issue a command that replaces the element #edit-output
        // with the rendered markup of the field created above.
        // ReplaceCommand() will take care of rendering our text field into HTML.
        $response->addCommand(new ReplaceCommand('#edit-outpout', $form['output']));

        // Show the dialog box.
        $response->addCommand(new OpenModalDialogCommand('My Title', $dialogText, ['width' => '300']));

        // Finally return the Ajax
        return $response;

    }

    // public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    // {

    //     $markup = 'nothing selected';
    //     if ($selectedValue = $form_state->getValue('example_select')) {
    //         $selectedText = $form['example_select']['#options'][$selectedValue];
    //         $markup = "<h2>$selectedText</h2>";
    //     }

    //     $response = new AjaxResponse();
    //     $response->addCommand(new ReplaceCommand('#edit-output', $markup));

    //     dump($selectedText);
    //     // exit();
    //     return $response;

    //     // return ['#markup' => $response];

    // }

    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {

        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
            $form['output']['#value'] = $selectedText;
        }

        // dump($selectedText);
        return $form['output'];

        ///////////

        // $markup = 'nothing selected';
        // if ($selectedValue = $form_state->getValue('example_select')) {
        //     $selectedText = $form['example_select']['#options'][$selectedValue];
        //     $markup = "<h1>$selectedText</h1>";
        // }
        // $output = "<div id='edit-output'>$markup</div>";
        // return ['#markup' => $output];
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
        // dump($form_state->getValues());
        foreach ($form_state->getValues() as $key => $value) {
            \Drupal::messenger()->addStatus($key . ': ' . $value);
        }
    }
}