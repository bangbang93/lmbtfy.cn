<?php

$pdo = new PDO('mysql:host=localhost;dbname='.MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);

$matches = [];
$count = preg_match('/s\/(.+)$/', $_SERVER['REQUEST_URI'], $matches);
if ($count < 1) {
    header('Location: /');
    die();
}
$id = $matches[1];

$stmt = $pdo->prepare('SELECT * from short_url where uniqId = :id LIMIT 1');
if (!$stmt->execute([':id' => $id])) {
    die('database error');
}

if ($stmt->rowCount() === 0) {
    header('Location: /');
    die();
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare('UPDATE short_url SET open = open + 1 where id = :id');
    $stmt->execute([':id' => $result['id']]);

    header('Location: /?q='. urlencode($result['keyword']));
    die();
}
