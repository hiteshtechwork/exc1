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

        // Personal details fields...
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

        $form['project'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Project Details'),
            '#prefix' => '<div id="projects-wrapper">',
            '#suffix' => '</div>',
        ];

        // Project fields...
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

        // Add button to dynamically add paragraph form elements
        $form['add_paragraph'] = [
            '#type' => 'button',
            '#value' => $this->t('Add Another Project Paragraph'),
            '#ajax' => [
                'callback' => '::addParagraphCallback',
                'wrapper' => 'paragraphs-wrapper',
            ],
        ];

        // Container to hold dynamically added paragraph forms
        $form['paragraphs_wrapper'] = [
            '#type' => 'container',
            '#attributes' => ['id' => 'paragraphs-wrapper'],
        ];

        // Add JavaScript to handle adding a new project
        $form['#attached']['library'][] = 'portfolio/listing_view_css';

        // Add submit button
        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    // Callback to add a new paragraph form element
    public function addParagraphCallback(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild();
        $form['paragraphs_wrapper'][] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Project Details'),
            'field_title' => [
                '#type' => 'textfield',
                '#title' => $this->t('Project title'),
                '#required' => false,
            ],
            'field_images' => [
                '#type' => 'managed_file',
                '#title' => $this->t('Project Images'),
                '#upload_location' => 'public://project_images/',
                '#upload_validators' => [
                    'file_validate_extensions' => ['png gif jpg jpeg'],
                ],
                '#multiple' => true,
            ],
            'field_description' => [
                '#type' => 'textarea',
                '#title' => $this->t('Project Description'),
                '#required' => false,
            ],
            'field_website_link' => [
                '#type' => 'url',
                '#title' => $this->t('Website link'),
                '#required' => false,
            ],
        ];
        return $form['paragraphs_wrapper'];
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $values = $form_state->getValues();

        // Create an array to store all paragraph entities
        $paragraphs = [];
//       echo "<pre>";
// print_r($values['field_title'] . "<br>");
// print_r($values['field_description'] . "<br>");
// print_r($values['field_images'] . "<br>");
// print_r($values['field_website_link'] . "<br>");
// echo "</pre>";
// exit();

        // Check if paragraphs wrapper exists in form values and it's not null

        echo "one";
        echo "<pre>";
        dump($values);
        print_r($values['projects-wrapper'] . "<br> first");
        print_r($values['paragraphs_wrapper'] . "<br> second");
        echo "</pre>";

        exit();
        // Iterate through the submitted paragraph values
        foreach ($values['paragraphs_wrapper'] as $paragraph_values) {

            echo "two";
            // Create a new Paragraph entity
            $paragraph = Paragraph::create([
                'type' => 'projects',
                'field_title' => $paragraph_values['field_title'],
                'field_description' => $paragraph_values['field_description'],
                'field_images' => $paragraph_values['field_images'],
                'field_website_link' => $paragraph_values['field_website_link'],
            ]);

            // Save the Paragraph entity
            $paragraph->save();
            echo "<pre>";
            print_r($paragraph . "<br>");
            echo "</pre>";

            // Add the paragraph entity to the array
            $paragraphs[] = $paragraph;
        }

        exit();
        // Create a new node of the "portfolio" content type
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
            'field_projects_ref' => array_map(function ($paragraph) {
                return [
                    'target_id' => $paragraph->id(),
                    'target_revision_id' => $paragraph->getRevisionId(),
                ];
            }, $paragraphs),
        ]);

        // Save the node entity
        $node->save();

        // Provide a message indicating successful node creation
        \Drupal::messenger()->addMessage($this->t('Portfolio node created successfully.'));
    }
}