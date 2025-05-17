?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fable - Clubs</title>
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
        <section class="club-banner">
            <h1>Rejoignez un club de lecture</h1>
            <p>Découvrez des communautés de lecteurs passionnés et échangez sur vos livres préférés.</p>
            <button class="create-club-btn">Créer un club</button>
        </section>
        <section class="club-list">
            <h2>Clubs populaires</h2>
            <div class="club-grid">
                <div class="club-card">
                    <img src="fantasy.jpg" alt="Club Fantasy">
                    <div class="club-info">
                        <h3>Club Fantasy</h3>
                        <p>Explorez les univers magiques et les sagas légendaires.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="scifi.jpg" alt="Club Science-Fiction">
                    <div class="club-info">
                        <h3>Club Science-Fiction</h3>
                        <p>Voyagez dans le futur et explorez les technologies avancées.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="classics.jpg" alt="Club Classiques">
                    <div class="club-info">
                        <h3>Club Classiques</h3>
                        <p>Redécouvrez les chefs-d'œuvre intemporels de la littérature.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="romance.jpg" alt="Club Romance">
                    <div class="club-info">
                        <h3>Club Romance</h3>
                        <p>Plongez dans les plus belles histoires d'amour et d'émotion.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="dark-romance.jpg" alt="Club Dark Romance">
                    <div class="club-info">
                        <h3>Club Dark Romance</h3>
                        <p>Explorez les histoires d’amour intenses, sombres et interdites.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="thriller.jpg" alt="Club Thriller">
                    <div class="club-info">
                        <h3>Club Thriller</h3>
                        <p>Plongez dans des récits haletants, pleins de suspense et de rebondissements.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
                <div class="club-card">
                    <img src="policier.jpg" alt="Club Policier">
                    <div class="club-info">
                        <h3>Club Policier</h3>
                        <p>Suivez les enquêtes, les détectives et les mystères criminels.</p>
                        <button class="join-club-btn">Rejoindre</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="script.js" defer></script>
</body>
</html>
