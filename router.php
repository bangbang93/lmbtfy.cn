<?php
if (php_sapi_name() !== 'cli-server') {
    die();
}

if (file_exists(__DIR__.$_SERVER['REQUEST_URI'])) {
    return false;
} else {
    require('s/index.php');
}

