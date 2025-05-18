<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    echo "ID du livre manquant.";
    exit;
}
require 'db.php';
echo "<!-- DB = " . $pdo->query("SELECT DATABASE()")->fetchColumn() . " -->";

$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    echo "Livre introuvable.";
    exit;
}

// Statut de lecture
$statut_actuel = null;
if (isset($_SESSION['user_id'])) {
    $stmtStatut = $pdo->prepare("SELECT statut FROM statuts_lecture WHERE user_id = ? AND livre_id = ?");
    $stmtStatut->execute([$_SESSION['user_id'], $livre['id']]);
    $row = $stmtStatut->fetch(PDO::FETCH_ASSOC);
    if ($row && isset($row['statut'])) {
        $statut_actuel = $row['statut'];
    }
}

// Commentaires fictifs
function genererCommentairesFictifs($n = 5) {
    $base_comments = [
        "Un livre captivant du début à la fin.",
        "Une lecture émotive, je recommande vivement.",
        "Le style de l’auteur est vraiment unique.",
        "Intrigue bien menée avec des rebondissements.",
        "Je me suis attaché aux personnages dès les premières pages.",
        "Un roman qui fait réfléchir profondément.",
        "Parfait pour un week-end au calme.",
        "Ce livre m’a donné des frissons !",
        "Belle écriture et histoire touchante.",
        "Un chef-d'œuvre moderne à découvrir.",
        "Chaque chapitre m’a tenu en haleine.",
        "Une pépite littéraire, tout simplement.",
        "Ce récit m’a profondément marqué.",
        "L’univers du roman est original et bien construit.",
        "Je le relirai avec plaisir.",
        "Une vraie claque émotionnelle.",
        "J'ai terminé ce livre en une nuit.",
        "Une atmosphère immersive et envoûtante.",
        "Un voyage intérieur bouleversant.",
        "Des personnages inoubliables.",
        "La narration est fluide et captivante.",
        "Un thème rarement traité avec autant de finesse.",
        "L’auteur nous plonge dans un monde saisissant.",
        "J’ai eu du mal à refermer le livre.",
        "Une fin inattendue qui m’a bouleversé.",
        "Très poétique et bien écrit.",
        "Il m’a accompagné dans mes réflexions personnelles.",
        "Une œuvre qui mérite d’être partagée.",
        "Lecture simple mais pleine d’impact.",
        "Des émotions fortes, sans tomber dans le cliché."
    ];
    $names = ["Clara", "Julien", "Emma", "Nathan", "Lina"];
    $emojis = ["📚", "❤️", "🤯", "👏", "✨"];

    shuffle($base_comments);
    $comments = [];

    for ($i = 0; $i < $n; $i++) {
        $name = $names[array_rand($names)];
        $content = $base_comments[$i];
        $emoji = $emojis[array_rand($emojis)];
        $stars = str_repeat("★", rand(3, 5));
        $comments[] = "<strong>$name</strong> – $stars<br>$emoji $content";
    }

    return $comments;
}

$commentaires = genererCommentairesFictifs();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($livre['title']) ?> | Fable</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .btn-noter {
        display: inline-block;
        padding: 10px 18px;
        background-color: #382110;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
        }
        .btn-noter:hover {
        background-color: #6b4e3d;
        }
    </style>

</head>
<body>
<?php include 'header.php'; ?>

<main class="book-page container">
   <!-- COUVERTURE ------------------------------------ -->
   <div class="cover-col">
      <img src="<?= htmlspecialchars($livre['image_url']) ?>"
           alt="Couverture de <?= htmlspecialchars($livre['title']) ?>"
           class="book-cover">
   </div>

   <!-- INFOS + ACTIONS -------------------------------- -->
   <div class="info-col">
      <h1 class="book-title"><?= htmlspecialchars($livre['title']) ?></h1>
      <p class="book-author">par <?= htmlspecialchars($livre['auteur']) ?></p>

      <!-- Note globale simulée -->
      <div class="rating-summary">
         <span class="avg-rating">4,2</span>
         <span class="stars">★★★★☆</span>
         <span class="rating-count">· 1 024 notes</span>
      </div>

      <!-- STATUT DE LECTURE (mêmes valeurs que la BDD) -->
      <?php if (isset($_SESSION['user_id'])): ?>
      <div class="read-actions">
         <label for="statut">Mon statut :</label>
         <select id="statut" onchange="mettreAJourStatut(this.value)">
            <?php foreach (['Pile à lire','En cours','Lu'] as $s): ?>
               <option value="<?= $s ?>" <?= $statut_actuel===$s?'selected':'' ?>>
                  <?= $s ?>
               </option>
            <?php endforeach; ?>
         </select>
         <?php if ($statut_actuel === 'Lu'): ?>
        <div class="rate-book" style="margin-top: 20px;">
            <a href="noter_livre.php?id=<?= $livre['id'] ?>" class="btn-noter">
            ⭐ Noter ce livre
            </a>
        </div>
        <?php endif; ?>

         <span id="message-statut" class="flash-msg"></span>
      </div>
      <?php else: ?>
         <p class="login-reminder">
            <a href="login.html">Connectez-vous</a> pour enregistrer votre progression.
         </p>
      <?php endif; ?>

      <!-- ONGLET RÉSUMÉ -------------------------------- -->
      <section class="book-section">
         <h2>Résumé</h2>
         <p><?= htmlspecialchars($livre['resume'] ?? 'Résumé indisponible.') ?></p>
      </section>

      <!-- ONGLET AUTEUR -------------------------------- -->
      <section class="book-section">
         <h2>À propos de l’autrice / de l’auteur</h2>
         <p><?= htmlspecialchars($livre['auteur_bio'] ?? 'Biographie non disponible.') ?></p>
      </section>
   </div>
</main>

<!-- COMMENTAIRES ------------------------------------- -->
<section class="comments container">
   <h2>Commentaires des lecteurs</h2>
   <?php foreach ($commentaires as $c): ?>
      <article class="comment"><?= $c ?></article>
   <?php endforeach; ?>
</section>

<!-- JS identique (on ne touche qu’au visuel) --------- -->
<script>
function mettreAJourStatut(s){
   const xhr = new XMLHttpRequest();
   xhr.open('POST','mettre_a_jour_statut.php',true);
   xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
   xhr.onload=function(){
      if(xhr.status===200) document.getElementById('message-statut').textContent='✓ enregistré';
   };
   xhr.send(`livre_id=<?= $livre['id'] ?>&statut=${encodeURIComponent(s)}`);
}
</script>


<section style="max-width: 1000px; margin: 50px auto;">
    <h2 style="border-bottom: 2px solid #ccc; padding-bottom: 10px;">Commentaires des lecteurs</h2>
    <ul style="list-style: none; padding: 0; margin-top: 20px;">
        <?php foreach ($commentaires as $comment): ?>
            <li style="background: #fffbe6; margin-bottom: 15px; padding: 15px 20px; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                <i class="fas fa-comment" style="margin-right: 10px; color: #c49b66;"></i>
                <?= $comment ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<script>
function mettreAJourStatut(statut) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "mettre_a_jour_statut.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("message-statut").innerText = "Statut mis à jour !";
            if (statut === 'En cours') {
                document.getElementById("lecture-progress").style.display = "block";
            } else {
                document.getElementById("lecture-progress").style.display = "none";
            }
            location.reload();
        }
    };
    xhr.send("livre_id=<?= $livre['id'] ?>&statut=" + encodeURIComponent(statut));
}

// Afficher ou non le champ selon le statut au chargement
document.addEventListener('DOMContentLoaded', function () {
    const statutSelect = document.getElementById('statut');
    if (statutSelect && statutSelect.value === 'En cours') {
        document.getElementById("lecture-progress").style.display = "block";
    }
});

function mettreAJourSuivi() {
    const page = document.getElementById("page_actuelle").value.trim();
    if (!page) return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "mettre_a_jour_suivi.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById("msg-suivi").innerText = "Progression enregistrée ✅";
        }
    };
    xhr.send("livre_id=<?= $livre['id'] ?>&progression=" + encodeURIComponent(page));
}
</script>

</body>
</html>

