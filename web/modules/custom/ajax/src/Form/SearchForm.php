<?php

namespace Drupal\ajax\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class SearchForm extends FormBase
{
    /**
     * {@inheritDoc}
     */

    public function getFormId()
    {
        return 'search_ajax_form';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        // prevent from reload on enter button
        $form['#attributes'] = [
            'onsubmit' => 'return false',
        ];

        // define the search field
        $form['serach_product'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Search product'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
            '#ajax' => [
                'callback' => '::updateSearchString',
                'disable-refoucs' => false,
                'event' => 'change',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Searching products...'),
                ],
            ],
        ];

        // Optionally display a submit button.
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit1'),
        ];

        return $form;
    }

    public function updateSearchString(array &$form, FormStateInterface $form_state)
    {

        $serachText = $form_state->getValue('serach_product');

        // Invoke a callback function
        $response = new AjaxResponse();
        $response->addCommand(new InvokeCommand(null, 'MyJavascriptCallbackFunction', [$serachText]));

        return $response;

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {

    }
}