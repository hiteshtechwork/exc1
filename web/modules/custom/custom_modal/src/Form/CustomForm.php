<?php
namespace Drupal\custom_modal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomForm extends FormBase
{

    public function getFormId()
    {
        return 'custom_modal_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $id = null)
    {
// id $id is provided, load existing data.

        $default_values = [];
        if ($id) {
            // assume we have a function to load data by ID.
            $default_values = $this->loadData($id);
        }

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#default_value' => $default_values['name'] ?? '',
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#default_value' => $default_values['email'] ?? '',
        ];

        $form['age'] = [
            '#type' => 'number',
            '#title' => $this->t('Age'),
            '#default_value' => $default_values['age'] ?? '',
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        ];

        return $form;

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();
        $this->saveData($values);
        \Drupal::messenger()->addMessage($this->t('Data saved successfully.'));
    }

    private function loadData($id)
    {
        return [
            'name' => 'John Doe',
            'email' => 'john@email.com',
            'age' => 30,
        ];

    }

    private function saveData($values)
    {
        dump($values);
    }

}