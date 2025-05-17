<?php
session_start();
var_dump($_SESSION);
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


$commentaires = genererCommentairesFictifs();
$statut_actuel = "Pile à lire";
$peut_noter = false;

if (isset($_SESSION['user_id'])) {
    $stmtStatut = $pdo->prepare("SELECT statut FROM statuts_lecture WHERE user_id = ? AND livre_id = ?");
    $stmtStatut->execute([$_SESSION['user_id'], $livre['id']]);
    $row = $stmtStatut->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $statut_actuel = $row['statut'];
        if (strtolower(trim($statut_actuel)) === "lu") {
            $peut_noter = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($livre['title']); ?> | Fable</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<header>
    <div class="logo">Fable</div>
    <nav>
        <ul>
            <li><a href="club.php">Clubs</a></li>
            <li><a href="search.php">Recherche</a></li>
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
            <a href="profile.php"><i class="fas fa-user" id="user-icon" style="cursor:pointer;"></i></a>
        <?php endif; ?>
    </div>
</header>

<main style="padding: 40px; max-width: 1000px; margin: auto; display: flex; gap: 40px;">
    <div style="flex-shrink: 0;">
        <img src="<?php echo htmlspecialchars($livre['image_url']); ?>" alt="Couverture du livre"
             style="width: 300px; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    </div>

    <div style="flex-grow: 1;">
        <h1 style="font-size: 32px; margin-bottom: 10px;"><?php echo htmlspecialchars($livre['title']); ?></h1>
        <p style="font-style: italic; color: #555;">par <?php echo htmlspecialchars($livre['auteur']); ?></p>

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

            <!-- Le bloc est toujours présent, masqué si nécessaire -->
            <div id="note-section" style="margin-top: 20px; display: <?php echo $peut_noter ? 'block' : 'none'; ?>;">
                <form action="noter_livre.php" method="post">
                    <input type="hidden" name="livre_id" value="<?php echo $livre['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                    <label for="note">Notez ce livre :</label>
                    <select name="note" id="note">
                        <option value="1">★☆☆☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="4">★★★★☆</option>
                        <option value="5">★★★★★</option>
                    </select>
                    <button type="submit">Envoyer</button>
                </form>
            </div>

            <script>
                function mettreAJourStatut(statut) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "mettre_a_jour_statut.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("message-statut").innerText = "Statut mis à jour !";
                            if (statut === "Lu") {
                                document.getElementById("note-section").style.display = "block";
                            } else {
                                document.getElementById("note-section").style.display = "none";
                            }
                        }
                    };
                    xhr.send("livre_id=<?php echo $livre['id']; ?>&user_id=<?php echo $_SESSION['user_id']; ?>&statut=" + encodeURIComponent(statut));
                }
            </script>
        <?php else: ?>
            <p class="statut-actuel">Connectez-vous pour suivre votre lecture.</p>
        <?php endif; ?>

        <h2 style="margin-top: 30px; font-size: 24px;">Résumé</h2>
        <p style="line-height: 1.6;"><?php echo isset($livre['resume']) ? htmlspecialchars($livre['resume']) : "Résumé indisponible."; ?></p>

        <h2 style="margin-top: 30px; font-size: 24px;">À propos de l'auteur</h2>
        <p style="line-height: 1.6;"><?php echo isset($livre['auteur_bio']) ? htmlspecialchars($livre['auteur_bio']) : "Biographie non disponible."; ?></p>


    </div>
</main>

<section style="max-width: 1000px; margin: 50px auto;">
    <h2 style="border-bottom: 2px solid #ccc; padding-bottom: 10px;">Commentaires des lecteurs</h2>
    <ul style="list-style: none; padding: 0; margin-top: 20px;">
        <?php foreach ($commentaires as $comment): ?>
            <li style="background: #fffbe6; margin-bottom: 15px; padding: 15px 20px; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                <i class="fas fa-comment" style="margin-right: 10px; color: #c49b66;"></i>
                <?php echo $comment; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

</body>
</html>