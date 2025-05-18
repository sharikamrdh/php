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
</head>
<body>
<?php include 'header.php'; ?>

<main style="padding: 40px; max-width: 1000px; margin: auto; display: flex; gap: 40px;">
    <div style="flex-shrink: 0;">
        <img src="<?= htmlspecialchars($livre['image_url']) ?>" alt="Couverture du livre"
             style="width: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    </div>
    <div style="flex-grow: 1;">
        <h1 style="font-size: 32px; margin-bottom: 10px;"><?= htmlspecialchars($livre['title']) ?></h1>
        <p style="font-style: italic; color: #555;">par <?= htmlspecialchars($livre['auteur']) ?></p>
        <p style="margin-top: 10px; font-size: 18px;">
            <strong>Note globale :</strong> 4.2 / 5 <i class="fas fa-star" style="color: #c49b66;"></i>
        </p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="statut-form">
                <label for="statut">Statut de lecture :</label>
                <select id="statut" onchange="mettreAJourStatut(this.value)">
                    <option value="Pile à lire" <?php if ($statut_actuel == "Pile à lire") echo "selected"; ?>>Pile à lire</option>
                    <option value="En cours" <?php if ($statut_actuel == "En cours") echo "selected"; ?>>En cours</option>
                    <option value="Lu" <?php if ($statut_actuel == "Lu") echo "selected"; ?>>Lu</option>
                </select>
                <p id="message-statut" class="statut-actuel"></p>
            </div>
        <?php else: ?>
            <p class="statut-actuel">Connectez-vous pour suivre votre lecture.</p>
        <?php endif; ?>

        <h2 style="margin-top: 30px; font-size: 24px;">Résumé</h2>
        <p style="line-height: 1.6;">
            <?= isset($livre['resume']) ? htmlspecialchars($livre['resume']) : "Résumé indisponible." ?>
        </p>

        <h2 style="margin-top: 30px; font-size: 24px;">A propos de l'auteur</h2>
        <p style="line-height: 1.6;">
            <?= isset($livre['auteur_bio']) ? htmlspecialchars($livre['auteur_bio']) : "Biographie non disponible." ?>
        </p>
    </div>
</main>

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
                location.reload();
            }
        };
        xhr.send("livre_id=<?= $livre['id'] ?>&user_id=<?= $_SESSION['user_id'] ?? 0 ?>&statut=" + encodeURIComponent(statut));
    }
</script>
</body>
</html>
