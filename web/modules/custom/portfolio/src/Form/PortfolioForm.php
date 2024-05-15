<?php

namespace Drupal\portfolio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a portfolio form.
 */
class PortfolioForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'portfolio1';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['personal_details'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Personal Details'),
        ];

        $form['personal_details']['field_firstname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#required' => false,
        ];

        $form['personal_details']['field_lastname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#required' => false,
        ];

        $form['personal_details']['field_email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => false,
        ];

        $form['personal_details']['field_profile_picture'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Profile Picture'),
            '#upload_location' => 'public://portfolio_pictures/',
            '#upload_validators' => [
                'file_validate_extensions' => ['png gif jpg jpeg'],
            ],
        ];

        $form['personal_details']['field_mobile_number'] = [
            '#type' => 'tel',
            '#title' => $this->t('Mobile Number'),
            '#required' => false,
        ];

        $form['personal_details']['field_address'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Address'),
            '#required' => false,
        ];

        $form['personal_details']['field_gender'] = [
            '#type' => 'select',
            '#title' => $this->t('Gender'),
            '#options' => [
                'male' => $this->t('Male'),
                'female' => $this->t('Female'),
            ],
            '#required' => false,
        ];

        $form['personal_details']['field_birth_date'] = [
            '#type' => 'date',
            '#title' => $this->t('Birth Date'),
            '#required' => false,
        ];

        $form['personal_details']['field_short_bio'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Short Bio'),
            '#required' => false,
        ];

        // $form['personal_details']['projects']['title'] = [
        //     '#type' => 'textarea',
        //     '#title' => $this->t('Projects'),
        //     '#required' => false,
        // ];

        $form['project'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Project Details'),
        ];
        $form['project']['field_title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Project title'),
            '#required' => false,
        ];
        $form['project']['field_images'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Project Images'),
            '#upload_location' => 'public://project_images/',
            '#upload_validators' => [
                'file_validate_extensions' => ['png gif jpg jpeg'],
            ],
            '#multiple' => true,
            // '#cardinality' => -1,
            // '#cardinality_multiple' => true,
        ];

        $form['project']['field_description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Project Description'),
            '#required' => false,
        ];
        $form['project']['field_website_link'] = [
            '#type' => 'url',
            '#title' => $this->t('Website link'),
            '#required' => false,
        ];

/////////////////////////////////////////////

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // Add form validation logic here if needed.
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        echo "<pre>";
        print_r($values);
        echo "</pre>";

        // exit();

        // Create the Paragraph entity
        $paragraph = Paragraph::create([
            'type' => 'projects',
            'field_title' => $values['field_title'],
            'field_description' => $values['field_description'],
            'field_images' => $values['field_images'],
            'field_website_link' => $values['field_website_link'],
        ]);
        $paragraph->save();

        // Create a new node of the "portfolio" content type.
        $node = Node::create([
            'type' => 'portfolio',
            'title' => $values['field_firstname'] . ' ' . $values['field_lastname'],
            'field_address' => $values['field_address'],
            'field_birth_date' => $values['field_birth_date'],
            'field_email' => $values['field_email'],
            'field_firstname' => $values['field_firstname'],
            'field_gender' => $values['field_gender'],
            'field_lastname' => $values['field_lastname'],
            'field_mobile_number' => $values['field_mobile_number'],
            'field_profile_picture' => $values['field_profile_picture'],
            'field_short_bio' => $values['field_short_bio'],
            'field_projects_ref' => [
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId(),
            ],

        ]);

        $node->save();

//         // Load or create the Paragraph entity
//         $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
//         $paragraph = $paragraph_storage->create([
//             'type' => 'Projects', // Replace with your paragraph type
//             'field_title' => $values['field_title'],
//             'field_description' => $values['field_description'],
//             'field_images' => $values['field_images'],
//             'field_website_link' => $values['field_website_link'],

//         ]);
// // Save the Paragraph entity
//         $paragraph->save();
// // Load the node entity where you want to set the reference
//         $node_storage = \Drupal::entityTypeManager()->getStorage('node');
//         $node = $node_storage->load($node_id); // Replace $node_id with the ID of your node
// // Set the Entity Reference Revision field value
//         if ($node) {
//             $node->set('field_paragraph_reference', [
//                 'target_id' => $paragraph->id(),
//                 'target_revision_id' => $paragraph->getRevisionId(),
//             ]);
//             $node->save();
//         }

        \Drupal::messenger()->addMessage($this->t('Portfolio node created successfully.'));
    }

}