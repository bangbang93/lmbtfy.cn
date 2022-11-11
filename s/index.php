<?php
require_once __DIR__.'/../vendor/autoload.php';

define('MYSQL_USERNAME', 'lmbtfy');
define('MYSQL_PASSWORD', 'lmbtfy');
define('MYSQL_DATABASE', 'lmbtfy');

$url = $_SERVER['REQUEST_URI'];
if ($url === '/s/create') {
    require __DIR__.'/./create.php';
} else {
    require __DIR__.'/./redirect.php';
}