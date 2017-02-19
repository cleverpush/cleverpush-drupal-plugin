<?php

namespace Drupal\cleverpush\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    public function getFormId()
    {
        return 'cleverpush_settings';
    }

    protected function getEditableConfigNames() {
        return [
            'cleverpush.settings',
        ];
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('cleverpush.settings');

        $form['subdomain'] = array(
            '#type' => 'textfield',
            '#title' => 'CleverPush Subdomain',
            '#description' => 'Bitte gib die Subdomain deines Kanals hier ein',
            '#default_value' => $config->get('subdomain'),
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Speichern',
            '#button_type' => 'primary',
        );
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('cleverpush.settings')
            ->set('subdomain', $form_state->getValue('subdomain'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}