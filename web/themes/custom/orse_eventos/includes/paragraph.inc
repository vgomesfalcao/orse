<?php

/**
 * @file
 * Theme and preprocess functions for paragraphs.
 */

/**
 * Implements hook_preprocess_paragraph__section().
 */
function orse_eventos_preprocess_paragraph__section(&$variables) {
  $variables['bgimage'] = $variables['content']['field_section_background'][0]['#markup'];
  $variables['bgcolor'] = $variables['content']['field_background_color']['#items']->first()->getValue();
  $variables['padding']['top'] = $variables['content']['field_padding_top']['#items']->first()->getValue()['value'];
  $variables['padding']['bottom'] = $variables['content']['field_padding_bottom']['#items']->first()->getValue()['value'];
}
