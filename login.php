<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie le mot de passe
        if (password_verify($password, $user['password'])) {
            // ✅ Connexion réussie : on démarre une session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php?message=Connexion réussie !");
            exit();
        } else {
            // ❌ Mot de passe incorrect
            header("Location: login.html?error=Mot de passe incorrect.");
            exit();
        }
    } else {
        // ❌ Email non trouvé
        header("Location: login.html?error=Email introuvable.");
        exit();
    }
}
?>

