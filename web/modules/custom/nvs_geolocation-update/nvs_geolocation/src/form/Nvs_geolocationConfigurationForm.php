<?php

namespace Drupal\nvs_geolocation\Form;

use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a settings for nvs_geolocation module.
 */
class Nvs_geolocationConfigurationForm extends ConfigFormBase {

  public function getEditableConfigNames() {
    return ['nvs_geolocation.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nvs_geolocation_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    global $base_url;
    $my_path = drupal_get_path('module', 'nvs_geolocation');


    $config = $this->config('nvs_geolocation.settings');

    
    $form = array();
    

    
    $form['googlemaps'] = array(
        '#type' => 'vertical_tabs',
        '#title' => t('Googlemaps settings'),
        '#open' => TRUE,
        
    );
    $form['nvs_geolocation_api_key_wr'] = array(
        '#type' => 'details',
        '#title' => t('API Key'),
        '#open' => TRUE,
        '#group' => 'googlemaps',
    );
    $form['nvs_geolocation_api_key_wr']['nvs_geolocation_api_key'] = array(
      '#title' => t('Googlemaps API Key'),
      '#type' => 'textfield',
      '#description' => t('How to <a href="https://developers.google.com/maps/documentation/embed/get-api-key">get API key</a><br>Please clear cache when changed'),
      '#default_value' => $config->get('nvs_geolocation_api_key'),
      
    );
    
    $form['nvs_geolocation_location_wr'] = array(
        '#type' => 'details',
        '#title' => t('Location'),
        '#open' => TRUE,
        '#group' => 'googlemaps',
    );
    $form['nvs_geolocation_location_wr']['nvs_geolocation_location_lat'] = array(
      '#title' => t('Latitude'),
      '#type' => 'textfield',
      '#description' => t('Eg: <em>47.670553</em>'),
      '#default_value' => $config->get('nvs_geolocation_location_lat'),
      
    );
    $form['nvs_geolocation_location_wr']['nvs_geolocation_location_lang'] = array(
      '#title' => t('Longitude'),
      '#type' => 'textfield',
      '#description' => t('Eg: <em>9.588479</em>'),
      '#default_value' => $config->get('nvs_geolocation_location_lang'),
      
    );
   
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity_types = '';
    $values = $form_state->getValues();
    $input_values = $form_state->getUserInput();
    $config = $this->config('nvs_geolocation.settings');


    $config->set('nvs_geolocation_api_key', $values['nvs_geolocation_api_key'])
      ->set('nvs_geolocation_location_lat', $values['nvs_geolocation_location_lat'])
      ->set('nvs_geolocation_location_lang', $values['nvs_geolocation_location_lang'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
