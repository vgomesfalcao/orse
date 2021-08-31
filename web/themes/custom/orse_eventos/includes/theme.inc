<?php

/**
 * @file
 * Custom theme hooks.
 */

/**
 * Implements hook_preprocess_html().
 */
function orse_eventos_preprocess_html(&$variables) {
  if (isset($variables["node_type"])) {
    array_push($variables["attributes"]["class"], $variables["node_type"]);
  }
}
