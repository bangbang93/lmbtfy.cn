<?php

$pdo = new PDO('mysql:host=localhost;dbname='.MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);

$matches = [];
$count = preg_match('/s\/(.+)$/', $_SERVER['REQUEST_URI'], $matches);
if ($count < 1) {
    header('Location: /');
    die();
}
$id = $matches[1];

$prepare = $pdo->prepare('SELECT * from short_url where uniqId = :id LIMIT 1');
if (!$prepare->execute([':id' => $id])) {
    die('database error');
}

$result = $prepare->fetch(PDO::FETCH_ASSOC);
if (count($result) === 0) {
    header('Location: /');
    die();
} else {
    header('Location: /?q='. urlencode($result['keyword']));
    die();
}
