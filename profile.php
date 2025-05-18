<?php
session_start();
require 'db.php';
echo "<!-- DB = " . $pdo->query("SELECT DATABASE()")->fetchColumn() . " -->";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?error=Connexion requise");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nb_objectif'])) {
    $nb = intval($_POST['nb_objectif']);
    $annee = date("Y");

    $stmt_check = $pdo->prepare("SELECT id FROM objectifs WHERE user_id = ? AND annee = ?");
    $stmt_check->execute([$user_id, $annee]);

    if ($stmt_check->fetch()) {
        $stmt_update = $pdo->prepare("UPDATE objectifs SET nb_livres_objectif = ? WHERE user_id = ? AND annee = ?");
        $stmt_update->execute([$nb, $user_id, $annee]);
    } else {
        $stmt_insert = $pdo->prepare("INSERT INTO objectifs (user_id, annee, nb_livres_objectif) VALUES (?, ?, ?)");
        $stmt_insert->execute([$user_id, $annee, $nb]);
    }

    header("Location: profile.php");
    exit();
}

$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$annee = date("Y");
$stmt_obj = $pdo->prepare("SELECT nb_livres_objectif FROM objectifs WHERE user_id = ? AND annee = ?");
$stmt_obj->execute([$user_id, $annee]);
$objectif = $stmt_obj->fetchColumn();

$stmt_lus = $pdo->prepare("SELECT COUNT(*) FROM statuts_lecture WHERE user_id = ? AND statut = 'Lu'");
$stmt_lus->execute([$user_id]);
$lus = $stmt_lus->fetchColumn();
$progression = ($objectif && $objectif > 0) ? min(100, round($lus / $objectif * 100)) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon profil - Fable</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #fdfaf6;
      color: #333;
    }
    header, .profile-section {
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    header {
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #6b4e3d;
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }
    nav ul li a {
      text-decoration: none;
      color: #6b4e3d;
      font-weight: 500;
    }
    .profile-header {
      display: flex;
      align-items: center;
      padding: 40px;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 30px;
    }
    .profile-info h1 {
      margin: 0;
      font-size: 28px;
    }
    .profile-nav {
      display: flex;
      gap: 20px;
      padding: 0 40px;
      border-bottom: 1px solid #eee;
    }
    .profile-nav a {
      padding: 10px 0;
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }
    .profile-nav a.active {
      border-bottom: 3px solid #6b4e3d;
    }
    section.content {
      padding: 40px;
    }
    .book-card, .club-card, .activity-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 20px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }
    .book-card img, .club-card img {
      width: 100px;
      height: 140px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card-info h3 {
      margin: 0;
      font-size: 18px;
      color: #6b4e3d;
    }
    .card-info p {
      margin: 5px 0;
      font-size: 14px;
      color: #555;
    }
    .stars {
      color: gold;
      font-size: 16px;
    }
    .objectif-lecture {
      background-color: #fffaf2;
      padding: 30px;
      border-radius: 16px;
      margin: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .objectif-lecture h2 {
      color: #382110;
      font-size: 24px;
      margin-bottom: 20px;
    }
    .objectif-form {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
    }
    .objectif-form input[type="number"] {
      width: 80px;
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .progress-container {
      margin-top: 20px;
    }
    .progress-bar {
      height: 20px;
      background-color: #eee;
      border-radius: 10px;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      background-color: #6b4e3d;
      width: 0%;
      transition: width 0.5s ease-in-out;
    }
    .progress-container p {
      margin-top: 8px;
      font-size: 16px;
      color: #382110;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">Fable</div>
  <nav>
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="search.php">Recherche</a></li>
      <li><a href="club.php">Clubs</a></li>
    </ul>
  </nav>
  <div class="icons">
    <a href="logout.php" title="Déconnexion"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</header>

<section class="profile-section">
  <div class="profile-header">
    <img src="default-profile.jpg" alt="Profil" class="profile-img">
    <div class="profile-info">
      <h1><?= htmlspecialchars($user['username']) ?></h1>
      <p><?= htmlspecialchars($user['email']) ?></p>
    </div>
  </div>
  <div class="profile-nav">
    <a class="active" href="#books">Mes livres</a>
    <a href="#clubs">Mes clubs</a>
    <a href="#activity">Activité</a>
  </div>
</section>

<main>
  <section class="objectif-lecture">
    <h2>🎯 Défi de lecture <?= $annee ?></h2>
    <form method="post" class="objectif-form">
      <label for="nb_objectif">Je veux lire</label>
      <input type="number" id="nb_objectif" name="nb_objectif" min="1" value="<?= htmlspecialchars($objectif ?: '') ?>" required>
      <span>livres en <?= $annee ?>.</span>
    </form>
    <?php if ($objectif): ?>
    <div class="progress-container">
      <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $progression ?>%;"></div>
      </div>
      <p><?= $lus ?> / <?= $objectif ?> livre(s) lus – <?= $progression ?>%</p>
    </div>
    <?php endif; ?>
  </section>

  <section id="books" class="content">
    <h2>Livres lus</h2>
    <div class="book-card">
      <img src="images/femme de menage.jpg" alt="Couverture livre">
      <div class="card-info">
        <h3>La femme de ménage</h3>
        <div class="stars">★★★★☆</div>
        <p>Un thriller captivant, très bien écrit.</p>
        <p><strong>Commentaire :</strong> J’ai adoré la tension constante.</p>
      </div>
    </div>
  </section>

  <section id="clubs" class="content">
    <h2>Mes clubs</h2>
    <div class="club-card">
      <img src="images/fantasy.jpg" alt="Club Fantasy">
      <div class="card-info">
        <h3>Club Fantasy</h3>
        <p>Discussions sur les romans fantastiques.</p>
      </div>
    </div>
  </section>

  <section id="activity" class="content">
    <h2>Activité récente</h2>
    <div class="activity-card">
      <div class="card-info">
        <p><strong>2025-05-10 :</strong> a noté "La femme de ménage" ★★★★☆</p>
        <p><strong>2025-05-08 :</strong> a rejoint le Club Fantasy</p>
      </div>
    </div>
  </section>
</main>
</body>
</html>


