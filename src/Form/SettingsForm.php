<?php

namespace Drupal\cleverpush\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
Use Drupal\Core\File\FileSystemInterface;

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
        $channelId = $form_state->getValue('channelId');

        $fileSystemConfig = \Drupal::config('system.file');
        $fileSystem = \Drupal::service('file_system');

        $directory = $fileSystemConfig->get('default_scheme') . '://cleverpush';

        $options = FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS;
        $fileSystem->prepareDirectory($directory, $options);

        $filenameWorker = $directory . '/cleverpush-worker.js';
        file_save_data('importScripts("https://static.cleverpush.com/channel/worker/' . $channelId . '.js");', $filenameWorker, FileSystemInterface::EXISTS_REPLACE);

        $this->config('cleverpush.settings')
            ->set('channelId', $channelId)
            ->set('workerFile', parse_url(file_create_url($filenameWorker), PHP_URL_PATH))
            ->save();

        parent::submitForm($form, $form_state);
    }
}
