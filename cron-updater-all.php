<?php

//Start the Session
session_start();

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'src/');
define('LOG_DIR', ROOT_DIR.'logs/');
define('CACHE_DIR', ROOT_DIR.'cache/');

// Includes
require(APP_DIR .'config/config.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/pfp.php');
require(ROOT_DIR .'vendor/autoload.php');

// Initialize Propel with the runtime configuration
Propel::init(APP_DIR."build/conf/rss-reader-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path(APP_DIR."build/classes" . PATH_SEPARATOR . get_include_path());

require(APP_DIR.'factories/CriteriaFactory.php');

function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler('handleError');

require (APP_DIR.'controllers/feed.php');

$feedController = new FeedController();
$feedController->forceUpdateAll();