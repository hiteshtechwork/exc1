<?php

namespace Drupal\custom_ajax\Form;

use Drupal\Core\Ajax\AddCssCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\DataCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomAjaxForm extends FormBase
{

    public function getFormId()
    {
        return 'custom_ajax_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#attributes' => [
                'id' => 'edit-name',
            ],
            '#ajax' => [
                'callback' => '::ajaxCallback',
                'event' => 'change',
            ],
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#attributes' => [
                'class' => ['custom-ajax'],
            ],
        ];

        // $form['message'] = [
        //     '#type' => 'textarea',
        //     '#title' => $this->t('Message'),
        // ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Handle form submission if needed.
    }

    public function ajaxCallback(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        // Add custom CSS dynamically to the name input field.
        $cssString = '<style>#edit-name.custom-ajax { background-color: yellow; color: red; }</style>';
        $response->addCommand(new AddCssCommand($cssString));

        // Add the custom class to the name input field.
        $response->addCommand(new InvokeCommand('#edit-name', 'addClass', ['custom-ajax']));

        /////////// after command

        $selector = '.custom-ajax';
        $content = '<p>My HTML content<p>';
        $settings = ['my-setting' => 'setting'];
        $response->addCommand(new HtmlCommand($selector, $content, $settings));

        ////////////// ---------data command
        // A CSS selector for the elements to which the data will be attached.
// Define the selector, key, and value for DataCommand.
        $selector = '#edit-name';
        $key = 'myKey';
        $value = ['some', 'set' => 'of', 'values'];

// Add the DataCommand to the response.
        $response->addCommand(new DataCommand($selector, $key, $value));

//////..........open dialog command

// The selector of the dialog.
        $selector = '#edit-name';
// The title of the dialog.
        $title = 'Dialog Title';
// The content that will be placed in the dialog, either a render array or an HTML string.
        $content = 'Some Content';
// (optional) Array of options to be passed to the dialog implementation. Any jQuery UI option can be used. See http://api.jqueryui.com/dialog.
        $dialog_options = [
            'minHeight' => 200,
            'resizable' => true,
        ];

        $settings = [];

        $response->addCommand(new OpenDialogCommand($selector, $title, $content, $dialog_options, $settings));

////////---------- announce command ...............

////        // Use Drupal.Announce to announce a message.
        // $response->addCommand(new MessageCommand('You made a goal!  Your score is now 8675309!', '#edit-name', ['announce' => '']));

        /////////////////////////
        // String: CSS selector for elements to be marked as changed.
        ///// (Optional) String: CSS selector for elements to which an asterisk will be appended.
        // $asterisk = '.myOtherClass';

        // $response->addCommand(new ChangedCommand($selector, $asterisk));

        // // A string that contains the text to display as a JavaScript alert.
        // $Text = 'My Text';
        // $response->addCommand(new AlertCommand($Text));

        ////////////...........

        // A string that contains the text to be announced by a screen reader.
        //         $Text = 'My Text';
        // // (optional) The priority that will be used for the announcement. Defaults to NULL which will not set a 'priority' in the response sent to the client and therefore the JavaScript Drupal.announce() default of 'polite' will be used for the message. Options: 'off','polite','assertive'. See https://www.w3.org/TR/wai-aria-1.1/#aria-live .
        //         $Priority = 'assertive';

        //         $response->addCommand(new AnnounceCommand($Text, $Priority));

        return $response;
    }

}