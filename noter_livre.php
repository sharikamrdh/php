<?php
session_start();
var_dump($_SESSION);
if (!isset($_POST['user_id'], $_POST['livre_id'], $_POST['note'])) {
    http_response_code(400);
    echo "Paramètres manquants.";
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

$user_id = intval($_POST['user_id']);
$livre_id = intval($_POST['livre_id']);
$note = intval($_POST['note']);

if ($note < 1 || $note > 5) {
    http_response_code(400);
    echo "Note invalide.";
    exit;
}

$stmt = $pdo->prepare("INSERT INTO notes (user_id, livre_id, note)
                       VALUES (?, ?, ?)
                       ON DUPLICATE KEY UPDATE note = VALUES(note)");
$stmt->execute([$user_id, $livre_id, $note]);

header("Location: livre.php?id=$livre_id");
exit;
