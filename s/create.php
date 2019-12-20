<?php

use PUGX\Shortid\Shortid;

$pdo = new PDO('mysql:host=localhost;dbname='.MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : null;
$keyword = trim($keyword);
if (empty($keyword)) {
    header('Location: /');
    die();
}

$prepare = $pdo->prepare('SELECT * from short_url where keyword = :keyword LIMIT 1');

if (!$prepare->execute([':keyword' => $keyword])) {
    die('database error');
}
$result = $prepare->fetch(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
if ($result !== false) {
    echo json_encode($result);
} else {
    $id = Shortid::generate(10);
    $prepare = $pdo->prepare('INSERT INTO short_url (uniqId, keyword) VALUES (:id, :keyword)');
    $prepare->execute([':id' => $id, ':keyword' => $keyword]);
    echo json_encode(['uniqId' => $id, 'keyword' => $keyword]);
}