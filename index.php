<?php
/*
 * PFP v1.0
 */

//Start the Session
session_start(); 

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');

// Includes
require(APP_DIR .'config/config.php');
require(ROOT_DIR .'system/pdo2.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pfp.php');

// Include the main Propel script
require_once(APP_DIR.'plugins/propel/runtime/lib/Propel.php');

// Initialize Propel with the runtime configuration
Propel::init(APP_DIR."build/conf/rss-reader-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path(APP_DIR."build/classes" . PATH_SEPARATOR . get_include_path());

pfp();

?>
