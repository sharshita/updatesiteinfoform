<?php

#create a new form to add the custom field

namespace Drupal\updatesiteinfoform\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm {
 
   /**
   * {@inheritdoc}
   */
	  public function buildForm(array $form, FormStateInterface $form_state) {
		$site_config = $this->config('system.site');
		$form =  parent::buildForm($form, $form_state);
		$form['site_information']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
			'#description' => t("Custom field to set the API Key"),
		];
		$site_config->get('siteapikey')
		
		return $form;
	}
	
	  public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->config('system.site')
		  ->set('siteapikey', $form_state->getValue('siteapikey'))
		  ->save();
		parent::submitForm($form, $form_state);
	  }
}

	function api_key_form_system_site_information_settings_alter(&$form, 
	 FormStateInterface $form_state, $form_id) {
	  if($form_id != 'system_site_information_settings') {
		return;
	  }
	$site_api = \Drupal::config('system.site')->get('siteapikey');
	$value = !empty($site_api)? t('Update Configuration'): t('Save Configuration');
	$form['#submit'][] = 'api_key_form_submit';
		  return $form;
	 }
 
    function api_key_form_submit($form, FormStateInterface $form_state) {
      $config = \Drupal::service('config.factory')->getEditable('system.site');
      $config->set('siteapikey', $form_state->getValue('siteapikey'));
      $config->save();
      //Prints the message.
	  $message = 'Site API Key has been saved';
      drupal_set_message($message,$type='status', $repeat=true);
    }
	

	
