<?php

/**
 * @file
 * Theme and preprocess functions for forms.
 */

/**
 * Implements hook_form_FORM_ID_alter() for system_theme_settings.
 */
function orse_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id = NULL) {
	// Agrupar em detalhes próprios.
	$form['orse_branding'] = [
		'#type' => 'details',
		'#title' => t('Orse branding'),
		'#open' => TRUE,
		'#tree' => TRUE,
	];


		$form['orse_branding']['color_primary'] = [
		'#type' => 'textfield',
		'#title' => t('Primary color'),
			'#default_value' => theme_get_setting('orse_branding.color_primary') ?: '#02542d',
		'#description' => t('Cor primária usada pelo tema.'),
	];

		$form['orse_branding']['color_secondary'] = [
		'#type' => 'textfield',
		'#title' => t('Secondary color'),
			'#default_value' => theme_get_setting('orse_branding.color_secondary') ?: '#bd6717',
		'#description' => t('Cor secundária/acento.'),
	];

		$form['orse_branding']['font_sans'] = [
		'#type' => 'textfield',
		'#title' => t('Sans-serif font stack'),
			'#default_value' => theme_get_setting('orse_branding.font_sans') ?: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji", sans-serif',
		'#description' => t('Ex.: "Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif"'),
	];
}
