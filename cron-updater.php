<?
// Use this script when your host only allows you to run scripts with cron tasks
require(realpath(dirname(__FILE__)).'/application/config/config.php');
$output = file_get_contents(BASE_URL.'feed/updateall');
?>