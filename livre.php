<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

// Génération de faux commentaires
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

    $fake_names = [
        "Clara", "Julien", "Fatou", "Lucas", "Emma", "Nina", "Mathieu", "Lina", "Sophie", "Nathan",
        "EclipseNoire", "BookLover91", "Dreamer23", "PenséeLumineuse", "LecteurX", "AuroreSilencieuse",
        "ChatonCurieux", "Mystik", "Nova_", "PetitKoala", "CaféLecture", "EspritLibre", "PlumeBleue",
        "FoudreLittéraire", "CoeurNomade", "PageTournée", "EdenLune", "OmbreDePapier", "RêveuseModerne"
    ];

    $emojis = ["📚", "❤️", "🤯", "👏", "💬", "✨", "😍", "👍", "🧠", "🔥"];

    shuffle($base_comments);
    $comments = [];

    for ($i = 0; $i < $n; $i++) {
        $name = $fake_names[array_rand($fake_names)];
        $content = $base_comments[$i];
        $emoji = $emojis[array_rand($emojis)];
        $stars = str_repeat("★", rand(3, 5));
        $comments[] = "<strong>$name</strong> – $stars<br>$emoji $content";
    }

    return $comments;
}

$commentaires = genererCommentairesFictifs(5);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $livre ? htmlspecialchars($livre['title']) : 'Livre introuvable' ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php if ($livre): ?>
    <div class="profile-section">
        <div class="livre-card">
            <img src="<?= htmlspecialchars($livre['image_url']) ?>" alt="<?= htmlspecialchars($livre['title']) ?>">
            <div>
                <h1><?= htmlspecialchars($livre['title']) ?></h1>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                <p><strong>Éditeur :</strong> <?= htmlspecialchars($livre['editeur']) ?></p>
                <p><strong>Date :</strong> <?= htmlspecialchars($livre['date']) ?></p>
                <p><strong>Langue :</strong> <?= htmlspecialchars($livre['langue']) ?></p>
                <p><strong>Genre :</strong> <?= htmlspecialchars($livre['genre']) ?></p>
                <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($livre['description'])) ?></p>
            </div>
        </div>
        <hr>
        <div class="commentaires">
            <h2>Commentaires des lecteurs</h2>
            <ul>
                <?php foreach ($commentaires as $com): ?>
                    <li><?= $com ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php else: ?>
    <p style="text-align: center;">Aucun livre trouvé avec cet ID.</p>
<?php endif; ?>
</body>
</html>

