<?php
session_start();
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
    <?php include 'header.php'; ?>

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
