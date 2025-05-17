<?php
require_once '../db.php';
header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$search = '%' . trim($_GET['q']) . '%';

try {
    $stmt = $pdo->prepare("SELECT id, title, auteur, genre, image_url FROM livres WHERE title LIKE :q OR auteur LIKE :q OR genre LIKE :q");
    $stmt->execute(['q' => $search]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
}
?>
