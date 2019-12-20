<?php
if (php_sapi_name() !== 'cli-server') {
    die();
}

define('MYSQL_USERNAME', 'bangbang93');
define('MYSQL_PASSWORD', 'bangbang93');
define('MYSQL_DATABASE', 'lmbtfy');

var_dump($_SERVER);
if (file_exists(__DIR__.$_SERVER['REQUEST_URI'])) {
    return false;
} else {
    require('s/index.php');
}

