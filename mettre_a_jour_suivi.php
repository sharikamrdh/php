<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Non connecté');
}

$user_id = intval($_SESSION['user_id']);
$livre_id = intval($_POST['livre_id'] ?? 0);
$progression = trim($_POST['progression'] ?? '');

if ($livre_id <= 0 || $progression === '') {
    http_response_code(400);
    exit('Données manquantes');
}

$stmt = $pdo->prepare(
    "INSERT INTO suivi_lecture (user_id, livre_id, progression)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE progression = VALUES(progression), date_update = CURRENT_TIMESTAMP"
);
$stmt->execute([$user_id, $livre_id, $progression]);

echo 'ok';
