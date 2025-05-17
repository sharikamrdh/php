<?php
session_start();
require 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Vous devez être connecté pour voir votre profil.");
    exit();
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Placeholder pour les livres lus et les clubs (à implémenter avec des tables supplémentaires si nécessaire)
$books_read = []; // À remplacer par une requête SQL si une table existe
$clubs_joined = [
    ['name' => 'Club Fantasy', 'image' => 'fantasy.jpg'],
    ['name' => 'Club Romance', 'image' => 'romance.jpg']
]; // Placeholder
$activities = [
    ['action' => 'a lu "La femme de ménage"', 'date' => '2025-05-10'],
    ['action' => 'a rejoint le Club Fantasy', 'date' => '2025-05-08']
]; // Placeholder
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fable - Profil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="script.js" defer></script>
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
            <?php endif; ?>
        </div>
    </header>
    <main>
        <section class="profile-section">
            <div class="profile-header">
                <img src="default-profile.jpg" alt="Profile Image" class="profile-img">
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <a href="logout.php" class="logout-btn">Déconnexion</a>
                </div>
            </div>
            <div class="profile-nav">
                <a href="#books" class="active">Mes livres</a>
                <a href="#clubs">Mes clubs</a>
                <a href="#activity">Activité</a>
            </div>
            <div class="profile-content">
                <div id="books" class="book-shelf">
                    <?php if (empty($books_read)): ?>
                        <p>Aucun livre lu pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($books_read as $book): ?>
                            <div class="book-card">
                                <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                <p><strong><?php echo htmlspecialchars($book['title']); ?></strong></p>
                                <p><?php echo htmlspecialchars($book['auteur']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div id="clubs" class="clubs-list">
                    <?php foreach ($clubs_joined as $club): ?>
                        <div class="club-card">
                            <img src="<?php echo htmlspecialchars($club['image']); ?>" alt="<?php echo htmlspecialchars($club['name']); ?>">
                            <div class="club-info">
                                <h3><?php echo htmlspecialchars($club['name']); ?></h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="activity" class="activity-feed">
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity">
                            <p><?php echo htmlspecialchars($user['username'] . ' ' . $activity['action']); ?></p>
                            <p class="date"><?php echo htmlspecialchars($activity['date']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    <script src="script.js" defer></script>
</body>
</html>
