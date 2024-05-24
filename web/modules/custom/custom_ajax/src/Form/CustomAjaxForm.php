<?php

namespace Drupal\custom_ajax\Form;

use Drupal\Core\Ajax\AddCssCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
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
                'wrapper' => 'ajax-custom-wrapper',
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

        // Attach the JavaScript file to the form.
        $form['#attached']['library'][] = 'custom_ajax/custom-ajax-dialog';

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // Handle form submission if needed.
    }
    public function ajaxCallback(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        // Define the content for the modal dialog with the submit button.

        $content = [
            '#markup' => '<div><p>Some content<h1>hello</h1></p>
            <button id="modal-submit-button">Submit</button><div>',

        ];

        // Attach the dialog library.
        $content['#attached']['library'][] = 'core/drupal.dialog.ajax';

        // Add command to open the modal dialog.
        $response->addCommand(new OpenModalDialogCommand('My Title', $content, ['width' => '300', 'autoOpen' => true, 'dialogClass' => 'custom-modal modal-close']));

        // Add command to close the modal when the submit button is clicked.
        $response->addCommand(new InvokeCommand('#modal-submit-button', 'click', []));
        // $response->addCommand(new CloseModalDialogCommand());

        return $response;
    }

    public function ajaxCallback2(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        // Attach the dialog library.
        $content['#attached']['library'][] = 'core/drupal.dialog.ajax';

        // Define the content for the modal dialog.
        // $content['#markup'] = '<p>Content for the modal dialog goes here.</p>';
        $content['#markup'] = "some content";

        // Add command to open the modal dialog.
        $response->addCommand(new OpenModalDialogCommand('My Title', $content, ['width' => '300', 'autoOpen' => true, 'dialogClass' => 'custom-modal modal-close']));

        return $response;
    }
    public function ajaxCallback1(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        $response->addCommand(new InvokeCommand('#edit-name', 'addClass', ['custom-ajax', 'custom-name-class']));

        $cssString = '<style>#edit-name.custom-ajax { background-color: yellow; color: red; }</style>';
        $response->addCommand(new AddCssCommand($cssString));

        return $response;

    }

}