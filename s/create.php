<?php

use PUGX\Shortid\Shortid;

$pdo = new PDO('mysql:host=localhost;dbname='.MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);

$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : null;
$keyword = trim($keyword);
if (empty($keyword)) {
    header('Location: /');
    die();
}

$stmt = $pdo->prepare('SELECT * from short_url where keyword = :keyword LIMIT 1');

if (!$stmt->execute([':keyword' => $keyword])) {
    die('database error');
}
$result = $stmt->fetch(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
if ($result !== false) {
    echo json_encode($result);
} else {
    do {
        $id = Shortid::generate(10);
        $stmt = $pdo->prepare('SELECT * from short_url where uniqId = :id');
        if (!$stmt->execute([':id' => $id])) {
            die('database error');
        }
    } while($stmt->rowCount() > 0);

    $stmt = $pdo->prepare('INSERT INTO short_url (uniqId, keyword) VALUES (:id, :keyword)');
    $stmt->execute([':id' => $id, ':keyword' => $keyword]);
    echo json_encode(['uniqId' => $id, 'keyword' => $keyword]);
}