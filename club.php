<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clubs de Lecture - Fable</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

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
                    <img src="images/club1.jpg" alt="Club Fantasy">
                    <div class="club-info">
                        <h3>Club Fantasy</h3>
                        <p>Explorez les univers magiques et les sagas légendaires.</p>
                        <a href="club_categorie.php?categorie=Fantasy" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club2.jpg" alt="Club Science-Fiction">
                    <div class="club-info">
                        <h3>Club Science-Fiction</h3>
                        <p>Voyagez dans le futur et explorez les technologies avancées.</p>
                        <a href="club_categorie.php?categorie=Science-Fiction" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club3.jpg" alt="Club Classiques">
                    <div class="club-info">
                        <h3>Club Classiques</h3>
                        <p>Redécouvrez les chefs-d'œuvre intemporels de la littérature.</p>
                        <a href="club_categorie.php?categorie=Classiques" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club4.jpg" alt="Club Romance">
                    <div class="club-info">
                        <h3>Club Romance</h3>
                        <p>Plongez dans les plus belles histoires d'amour et d'émotion.</p>
                        <a href="club_categorie.php?categorie=Romance" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club5.jpg" alt="Club Dark Romance">
                    <div class="club-info">
                        <h3>Club Dark Romance</h3>
                        <p>Explorez les histoires d’amour intenses, sombres et interdites.</p>
                        <a href="club_categorie.php?categorie=Dark%20Romance" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club6.jpg" alt="Club Thriller">
                    <div class="club-info">
                        <h3>Club Thriller</h3>
                        <p>Plongez dans des récits haletants, pleins de suspense et de rebondissements.</p>
                        <a href="club_categorie.php?categorie=Thriller" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
                <div class="club-card">
                    <img src="images/club7.jpg" alt="Club Policier">
                    <div class="club-info">
                        <h3>Club Policier</h3>
                        <p>Suivez les enquêtes, les détectives et les mystères criminels.</p>
                        <a href="club_categorie.php?categorie=Policier" class="join-club-btn">Rejoindre</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
