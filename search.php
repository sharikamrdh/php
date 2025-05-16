<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search = '%' . trim($_GET['q']) . '%';

    try {
        $stmt = $pdo->prepare("
            SELECT id, title, auteur, genre, image_url 
            FROM livres 
            WHERE title LIKE :title 
            OR auteur LIKE :auteur 
            OR genre LIKE :genre
        ");
        $stmt->execute([
            'title' => $search,
            'auteur' => $search,
            'genre' => $search
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
    }
    exit;
}

// Si pas de requête AJAX, renvoyer le HTML
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fable - Recherche</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">Fable</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Recherche</a></li>
                <li><a href="club.php">Clubs</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">For Business</a></li>
                <li><a href="#">For Education</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="login.html" class="login-btn">Sign In</a></li>
                    <li><a href="signup.html" class="signup-btn">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php"><i class="fas fa-user" id="user-icon"></i></a>
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
            <i class="fas fa-shopping-cart"></i>
        </div>
    </header>
    <main>
        <section class="search-section">
            <h1>Rechercher un livre</h1>
            <p>Entrez le titre, l'auteur ou le genre pour trouver un livre.</p>
            <form id="search-form">
                <input type="text" id="search-box" name="search" placeholder="Rechercher un livre..." required>
                <button type="submit">Rechercher</button>
            </form>
            <div class="genre-buttons">
                <button type="button">Romance</button>
                <button type="button">Thriller</button>
                <button type="button">Policier</button>
                <button type="button">Historique</button>
                <button type="button">Roman</button>
                <button type="button">Fantastique</button>
                <button type="button">Essai</button>
                <button type="button">Manga</button>
                <button type="button">Bande dessinée</button>
                <button type="button">Cuisine</button>
                <button type="button">Activité</button>
                <button type="button">Jeunesse</button>
                <button type="button">Théâtre</button>
                <button type="button">Journal</button>
                <button type="button">Religion</button>
                <button type="button">Référence</button>
                <button type="button">Recueil</button>
                <button type="button">Scolaire</button>
            </div>
            <div id="search-results"></div>
        </section>
    </main>
    <script src="script.js" defer></script>
</body>
</html>