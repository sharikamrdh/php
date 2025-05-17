<?php
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$terme = '%' . trim($_GET['q']) . '%';

try {
    $stmt = $pdo->prepare("SELECT title FROM livres WHERE title LIKE ? LIMIT 10");
    $stmt->execute([$terme]);
    $titres = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($titres);
} catch (PDOException $e) {
    echo json_encode([]);
}
