<?php
// This file generated by Propel 1.7.1-dev convert-conf target
// from XML runtime conf file /var/www/rss-reader/application/runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'rss-reader' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'dsn' => 'mysql:host=localhost;dbname=rss-reader',
        'user' => 'root',
        'password' => '',
      ),
    ),
    'default' => 'rss-reader',
  ),
  'generator_version' => '1.7.1',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-rss-reader-conf.php');
return $conf;