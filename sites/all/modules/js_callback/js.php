<?php
/**
 * @file
 * Callback page that serves custom JavaScript requests on a Drupal installation.
 */

/**
 * @var The Drupal root
 */
define('DRUPAL_ROOT', getcwd());

/**
 * @name Return constants, copies from menu.inc.
 * @{
 * The constants are copied to be able to drop the menu.inc depdendency
 */
define('JS_MENU_NOT_FOUND', 2);
define('JS_MENU_ACCESS_DENIED', 3);
/**
 * @} End of "Required core files".
 */

/**
 * @name Required core files
 * @{
 * The minimal core files required to be able to run a js request
 */
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/common.inc';
require_once DRUPAL_ROOT . '/includes/module.inc';
require_once DRUPAL_ROOT . '/includes/unicode.inc';
require_once DRUPAL_ROOT . '/includes/file.inc';
/**
 * @} End of "Required core files".
 */

// Do basic bootstrap to make sure the database can be accessed
drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);

// Prevent Devel from hi-jacking our output in any case.
$GLOBALS['devel_shutdown'] = FALSE;

$return = js_execute_callback();

// Menu status constants are integers; page content is a string.
if (is_int($return)) {
  // Make sure the full bootstrap has ran
  drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

  // Deliver error page
  drupal_deliver_page($return);
}
elseif (isset($return)) {
  // If JavaScript callback did not exit, print any value (including an empty
  // string) except NULL or undefined:
  drupal_json_output($return);
}

/**
 * Loads the requested module and executes the requested callback.
 *
 * @return
 *   The callback function's return value or one of the JS_* constants.
 */
function js_execute_callback() {
  $args = explode('/', $_GET['q']);

  // Strip first argument 'js'.
  array_shift($args);

  // Determine module to load.
  $module = check_plain(array_shift($args));
  if (!$module || !drupal_load('module', $module)) {
    return JS_MENU_ACCESS_DENIED;
  }

  // Get info hook function name.
  $function = $module . '_js';
  if (!function_exists($function)) {
    return JS_MENU_NOT_FOUND;
  }

  // Get valid callbacks.
  $valid_callbacks = $function();
  //Validate the availability of the callback
  $callback_args = $args;
  $callback_valid = FALSE;
  while (!empty($callback_args)) {
    $callback = check_plain(implode('/', $callback_args));
    if (isset($valid_callbacks[$callback])) {
      break;
    }
    else {
      // pop another parameter off the incoming args and check again
      array_pop($callback_args);
    }
  }

  //Validate the callback
  if (!isset($valid_callbacks[$callback])) {
    return JS_MENU_NOT_FOUND;
  }

  // If the callback function is located in another file, load that file now.
  if (isset($valid_callbacks[$callback]['file']) && ($filepath = drupal_get_path('module', $module) . '/' . $valid_callbacks[$callback]['file']) && file_exists($filepath)) {
    require_once $filepath;
  }

  // Validate the existance of the defined callback
  if (!function_exists($valid_callbacks[$callback]['callback'])) {
    return JS_MENU_NOT_FOUND;
  }

  // Bootstrap to required level.
  $full_boostrap = FALSE;
  if (!empty($valid_callbacks[$callback]['bootstrap'])) {
    drupal_bootstrap($valid_callbacks[$callback]['bootstrap']);
    $full_boostrap = ($valid_callbacks[$callback]['bootstrap'] == DRUPAL_BOOTSTRAP_FULL);
  }

  if (!$full_boostrap) {
    // The following mimics the behavior of _drupal_bootstrap_full().
    // The difference is that not all modules and includes are loaded
    // @see _drupal_bootstrap_full()

    // Load required include files based on the callback
    if (isset($valid_callbacks[$callback]['includes']) && is_array($valid_callbacks[$callback]['includes'])) {
      foreach ($valid_callbacks[$callback]['includes'] as $include) {
        if (file_exists("./includes/$include.inc")) {
          require_once "./includes/$include.inc";
        }
      }
    }

    // Detect string handling method.
    unicode_check();

    // Undo magic quotes.
    fix_gpc_magic();

    // Make sure all stream wrappers are registered.
    file_get_stream_wrappers();

    // Load required modules.
    $modules = array($module => 0);
    if (isset($valid_callbacks[$callback]['dependencies']) && is_array($valid_callbacks[$callback]['dependencies'])) {
      foreach ($valid_callbacks[$callback]['dependencies'] as $dependency) {
        if (!drupal_load('module', $dependency)) {
          throw new Exception(t('Error, the dependancy (!module) for this callback is not installed.', array('!module' => $dependency)));
        }
        $modules[$dependency] = 0;
      }
    }
    // Reset module list.
    module_list(FALSE, TRUE, FALSE, $modules);

    // If access arguments are passed, boot to SESSION and validate if the user
    // has access to this callback
    if(!empty($valid_callbacks[$callback]['access arguments']) || !empty($valid_callbacks[$callback]['access callback'])) {
      drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);

      // If no callback is provided, default to user_access
      if (!isset($valid_callbacks[$callback]['access callback'])) {
        $valid_callbacks[$callback]['access callback'] = 'user_access';
      }

      if($valid_callbacks[$callback]['access callback'] == 'user_access') {
        // Ensure the user module is available
        drupal_load('module', 'user');
      }

      if(!call_user_func_array($valid_callbacks[$callback]['access callback'], $valid_callbacks[$callback]['access arguments'])) {
        return JS_MENU_ACCESS_DENIED;
      }
    }
    
    // Invoke implementations of hook_init().
    module_invoke_all('init');
  }

  // Invoke callback function.
  return call_user_func_array($valid_callbacks[$callback]['callback'], $args);
}
