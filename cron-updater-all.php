<?
// Use this script when your host only allows you to run scripts with cron tasks
require(realpath(dirname(__FILE__)).'/application/config/config.php');
set_time_limit(0);
file_get_contents(BASE_URL.'feed/forceupdateall/asc');
file_get_contents(BASE_URL.'feed/forceupdateall/desc');
?>