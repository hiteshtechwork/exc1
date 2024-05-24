<?php

namespace Drupal\custom_modal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class CustomController extends ControllerBase
{
    public function edit($id, Request $request)
    {
        $form = \Drupal::formBuilder()->getForm('\Drupal\custom_modal\Form\CustomForm', $id);
        return [
            '#theme' => 'custom_modal_template',
            '#attached' => [
                'library' => [
                    'core/drupal.dialog.ajax',
                    'custom_modal/custom_modal',
                ],
            ],
            '#form' => $form,
        ];
    }
}