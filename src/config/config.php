<?php

define('INSTALLED', 'true');

define('BASE_URL', 'http://rss.local/'); // Base URL including trailing slash (e.g. http://localhost/)

define('DEFAULT_CONTROLLER', 'main'); // Default controller to load
define('ERROR_CONTROLLER', 'error'); // Controller used for errors (e.g. 404, 500 etc)

define('PROJECT_NAME', 'RSS Reader');

define('DEFAULT_AUTHOR', 'Rémy Gardette'); // Default author name, displayed as meta author
define('DEFAULT_TITLE', PROJECT_NAME.''); // Default page title, displayed in head title
define('DEFAULT_DESCRIPTION', 'RSS Reader - A self-hosted RSS aggregator and reader'); // Default page description, displayed as meta description

define('HTTPS', 'false');

define('PROXY_SALT', 'your-proxy-salt');

define('PROXY_URL', BASE_URL.'entry/proxy?url=');
