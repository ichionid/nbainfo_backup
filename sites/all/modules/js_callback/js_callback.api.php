<?php

/**
 * @file
 *
 * This file contains no working PHP code; it exists to provide additional documentation
 * for doxygen as well as to document hooks in the standard Drupal manner.
 */

/**
 * Register JS callbacks.
 *
 * @return array
 *   An array of callbacks with the following possible keys for each callback:
 *   - callback: (required) The function to call to display the results when an ajax call occurs on this path
 *   - includes: (optional) Load aditional files from the /includes directory
 *   - dependancies: (optional) Load additional modules for this callback
 */
function hook_js() {
  return array(
    'somefunction' => array(
      'callback' => 'example_somefunction',
      'includes' => array('includefile1', 'includefile2'),
      'dependencies' => array('module1', 'module2'),
    ),
  );
}
