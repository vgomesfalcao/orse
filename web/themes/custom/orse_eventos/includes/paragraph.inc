<?php

/**
 * @file
 * Theme and preprocess functions for paragraphs.
 */

/**
 * Implements hook_preprocess_paragraph__section().
 */
function orse_eventos_preprocess_paragraph__section(&$variables) {
  $variables['bgcolor'] = $variables['content']['field_background_color']['#items']->first()->getValue();
}
