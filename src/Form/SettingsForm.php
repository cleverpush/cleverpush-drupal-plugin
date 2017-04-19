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

        $form['channelId'] = array(
            '#type' => 'textfield',
            '#title' => 'CleverPush Kanal ID',
            '#description' => 'Bitte gib die ID deines Kanals hier ein',
            '#default_value' => $config->get('channelId'),
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
            ->set('channelId', $form_state->getValue('channelId'))
            ->save();

        parent::submitForm($form, $form_state);
    }
}