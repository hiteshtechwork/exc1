<?php

namespace Drupal\ajax\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AddCssCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface as DependencyInjectionContainerInterface;

/**
 * Provides a default form.
 */
class CustomAjaxForm extends FormBase
{

    /**
     * The renderer service.
     *
     * @var \Drupal\Core\Render\RendererInterface
     */
    protected $renderer;

    /**
     * Constructs a new CustomAjaxForm.
     *
     * @param \Drupal\Core\Render\RendererInterface $renderer
     *   The renderer service.
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(DependencyInjectionContainerInterface $container)
    {
        return new static(
            $container->get('renderer')
        );
    }

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
                // 'effect' => 'fade',
                'speed' => '10000',

                'progress' => [
                    'type' => 'throbber', //bar
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

        // Create a link that opens a modal.
        $url = Url::fromRoute('ajax.search');
        $link = Link::fromTextAndUrl($this->t('Make mine a modal'), $url)->toRenderable();
        $link['#attributes'] = [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => Json::encode([
                'width' => 700,
                // 'height' => 700,
            ]),
        ];

        $form['modal_link'] = [
            '#type' => 'markup',
            '#markup' => $this->renderer->render($link),
        ];

        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return $form;
    }

    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();
        $markup = 'nothing';
        if ($selectedValue = $form_state->getValue('example_select')) {
            $selectedText = $form['example_select']['#options'][$selectedValue];
            $markup = "<h2>$selectedText</h2>";
        }
        $output = "<div id='edit-output-wrapper'>$markup</div>";
        $response->addCommand(new ReplaceCommand('#edit-output-wrapper', $output));
        // A string that contains the styles to be added to the page, including the wrapping <style> tag.
        $CssString = '<style>.myclass{color:red;}</style>';
        $response->addCommand(new AddCssCommand($CssString));

        return $response;
    }

/**
 * AJAX callback handler for the select element.
 */
    // public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    // {
    //     $selectedText = 'nothing selected';
    //     if ($selectedValue = $form_state->getValue('example_select')) {
    //         $selectedText = $form['example_select']['#options'][$selectedValue];
    //     }

    //     $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
    //     $dialogText['#markup'] = "You selected : $selectedText";

    //     $response = new AjaxResponse();
    //     $response->addCommand(new ReplaceCommand('#edit-output-wrapper', $form['output']));
    //     $response->addCommand(new OpenModalDialogCommand('My TITLE', $dialogText, ['widht' => '300']));
    //     return $response;

    // }

    /**
     * AJAX callback handler for the select element.
     */
    // public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    // {
    //     // Create a new AjaxResponse object to build the AJAX response.
    //     $response = new AjaxResponse();

    //     // Initialize $markup with a default value.
    //     $markup = 'nothing';

    //     // Check if a value is selected in the 'example_select' element.
    //     if ($selectedValue = $form_state->getValue('example_select')) {
    //         // Get the selected text corresponding to the selected value.
    //         $selectedText = $form['example_select']['#options'][$selectedValue];
    //         // Create markup to display the selected text in an <h2> element.
    //         $markup = "<h2>$selectedText</h2>";
    //     }

    //     // Wrap the $markup in a div element with the ID 'edit-output-wrapper'.
    //     $output = "<div id='edit-output-wrapper'>$markup</div>";

    //     // Create a ReplaceCommand to update the content of the element with ID 'edit-output-wrapper'.
    //     $response->addCommand(new ReplaceCommand('#edit-output-wrapper', $output));

    //     // Return the AjaxResponse object.
    //     return $response;
    // }

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