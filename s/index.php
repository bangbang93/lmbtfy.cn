<?php
die('123');
require_once __DIR__.'/../vendor/autoload.php';

$url = $_SERVER['REQUEST_URI'];
if ($url === '/s/create') {
    require __DIR__.'/./create.php';
} else {
    require __DIR__.'/./redirect.php';
}