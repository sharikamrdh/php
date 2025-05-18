<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

if (!isset($_SESSION['user_id'], $_POST['livre_id'], $_POST['message'])) {
    die("Paramètres manquants ou utilisateur non connecté.");
}

$user_id = $_SESSION['user_id'];
$livre_id = intval($_POST['livre_id']);
$message = trim($_POST['message']);

if ($message === '') {
    header("Location: club_livre.php?id=$livre_id");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO messages_club (user_id, livre_id, message) VALUES (?, ?, ?)");
$stmt->execute([$user_id, $livre_id, $message]);

header("Location: club_livre.php?id=$livre_id");
exit;
