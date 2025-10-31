<?php

/**
 * @file
 * Custom theme hooks.
 */

/**
 * Implements hook_library_info_build().
 *
 * Automatically creates components libraries (Not visible in theme's
 * libraries.yml file). You can directly use the component name as a library,
 * f.x {{ attach_library('drupal_boilerplate_developing_tests/block') }}.
 */
function orse_library_info_build() {
  $extensions = ['css', 'js'];
  $directory = 'themes/custom/orse/build/components';
  $extensions = array_map('preg_quote', $extensions);
  $extensions = implode('|', $extensions);
  if (!is_dir($directory)) {
    return [];
  }
  $file_scan = \Drupal::service('file_system')->scanDirectory($directory, "/{$extensions}$/");
  $libraries = [];
  foreach ($file_scan as $file) {
    $parts = explode('.', $file->filename);
    $extension = end($parts);
    switch ($extension) {
      case 'css':
        $libraries[$file->name][$extension] = [
          'component' => [
            '/' . $file->uri => [],
          ],
        ];
        break;

      case 'js':
        $libraries[$file->name][$extension] = [
          '/' . $file->uri => [],
        ];
        break;
    }
  }
  return $libraries;
}

/**
 * Implements hook_preprocess_html().
 */
function orse_preprocess_html(array &$variables) {
  $config = \Drupal::config('system.theme');
  $active_theme_name = $config->get('default');
  // Apenas se o tema ativo for este tema.
  if ($active_theme_name !== 'orse') {
    return;
  }

  $settings = \Drupal::config('orse.settings');
  // Buscar valores com fallback aos padrões do SCSS.
  $primary = $settings->get('orse_branding.color_primary') ;
  $secondary = $settings->get('orse_branding.color_secondary') ;
  $font_sans = $settings->get('orse_branding.font_sans') ?? 'inter, sans-serif';

  $css = ":root{ --orse-primary: {$primary}; --orse-secondary: {$secondary}; --orse-font-sans: {$font_sans};}";

  $element = [
    '#type' => 'html_tag',
    '#tag' => 'style',
    '#value' => $css,
    '#attributes' => [
      'data-origin' => 'orse-theme-settings',
    ],
  ];
  $variables['#attached']['html_head'][] = [$element, 'orse_theme_settings_css_variables'];
}
