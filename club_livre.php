<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=fable_db;charset=utf8", "root", "");
// S'assurer que des utilisateurs fictifs existent
$fictifs = [
    ['id' => 1, 'username' => 'Alice'],
    ['id' => 2, 'username' => 'Léo'],
    ['id' => 3, 'username' => 'Sofiane'],
    ['id' => 4, 'username' => 'Emma'],
    ['id' => 5, 'username' => 'Yassine']
];

foreach ($fictifs as $user) {
    $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmtCheck->execute([$user['id']]);
    if (!$stmtCheck->fetch()) {
        $stmtInsert = $pdo->prepare("INSERT INTO users (id, username, email, password) VALUES (?, ?, ?, '')");
        $stmtInsert->execute([$user['id'], $user['username'], $user['username'] . '@fable.fr']);
    }
}

$livre_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les informations du livre
$stmtLivre = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmtLivre->execute([$livre_id]);
$livre = $stmtLivre->fetch();
if (!$livre) {
    die("Livre non trouvé.");
}

// Nombre de membres dans ce club
$stmtMembres = $pdo->prepare("SELECT COUNT(*) AS total FROM bookclub_members WHERE livre_id = ?");
$stmtMembres->execute([$livre_id]);
$total_membres = $stmtMembres->fetchColumn();

// Récupérer les messages de chat
$stmtMessages = $pdo->prepare("SELECT m.message, m.date_message, u.username
    FROM messages_club m
    JOIN users u ON m.user_id = u.id
    WHERE m.livre_id = ? ORDER BY m.date_message DESC");
$stmtMessages->execute([$livre_id]);
$messages = $stmtMessages->fetchAll();

// Ajouter des messages factices si aucun n'est présent pour ce livre
if (count($messages) === 0) {
    $faux_messages = [
        "Ce livre m’a transporté, je l’ai dévoré en un week-end !",
        "La tension est incroyable, surtout à partir du troisième chapitre.",
        "Les personnages sont si bien construits, on s’y attache vraiment.",
        "Quel retournement de situation à la fin !",
        "Je l’ai relu deux fois pour mieux comprendre la psychologie du héros.",
        "Je ne m’attendais pas à une histoire aussi intense.",
        "Ce club est parfait pour échanger nos ressentis, j’adore ❤️",
        "C’est le livre qui m’a redonné envie de lire."
    ];
    $user_ids = [1, 2, 3, 4, 5]; // Remplacer par les user_id valides existants dans ta table users
    $insert = $pdo->prepare("INSERT INTO messages_club (user_id, livre_id, message, date_message) VALUES (?, ?, ?, ?)");

    foreach ($faux_messages as $text) {
        $user_id = $user_ids[array_rand($user_ids)];
        $date = date('Y-m-d H:i:s', strtotime("-" . rand(1, 240) . " minutes"));
        $insert->execute([$user_id, $livre_id, $text, $date]);
    }

    $stmtMessages->execute([$livre_id]);
    $messages = $stmtMessages->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Club - <?= htmlspecialchars($livre['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f2ef;
        }
        .book-details {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            background: #fff;
            padding: 50px;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
        }
        .book-details img {
            width: 250px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .book-details div {
            max-width: 600px;
        }
        .book-details h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .book-details p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .chat-club {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            width: 70%;
            margin: 40px auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }
        .chat-club h2 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 25px;
        }
        .messages {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background: #fafafa;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column-reverse;
            gap: 12px;
        }
        .message {
            background-color: #fdfdfd;
            border-radius: 8px;
            padding: 12px 15px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .message strong {
            color: #2c3e50;
            font-size: 15px;
        }
        .message span {
            font-size: 12px;
            color: #aaa;
            float: right;
        }
        .message p {
            margin-top: 6px;
            font-size: 14px;
        }
        form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            resize: vertical;
            font-size: 14px;
        }
        form button {
            margin-top: 12px;
            padding: 10px 20px;
            background-color: #3e2a1c;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }
        form button:hover {
            background-color: #2b1c13;
        }
    </style>
    <script>
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMessages = doc.querySelector('.messages');
                    document.querySelector('.messages').innerHTML = newMessages.innerHTML;
                });
        }, 5000);
    </script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="book-details">
            <img src="<?= $livre['image_url'] ?: 'images/default-book-cover.jpg' ?>" alt="<?= htmlspecialchars($livre['title']) ?>">
            <div>
                <h1><?= htmlspecialchars($livre['title']) ?></h1>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                <p><strong>Genre :</strong> <?= htmlspecialchars($livre['genre']) ?></p>
                <p><strong>Membres dans ce club :</strong> <?= $total_membres ?></p>
                <p><strong>Résumé :</strong> <?= nl2br(htmlspecialchars($livre['resume'])) ?></p>
            </div>
        </section>

        <section class="chat-club">
            <h2>Discussion du club</h2>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="chat_club.php" method="POST">
                    <input type="hidden" name="livre_id" value="<?= $livre_id ?>">
                    <textarea name="message" rows="3" placeholder="Votre message..." required></textarea>
                    <button type="submit">Envoyer</button>
                </form>
            <?php else: ?>
                <p>Connectez-vous pour participer à la discussion.</p>
            <?php endif; ?>

            <div class="messages">
                <?php foreach ($messages as $msg): ?>
                    <div class="message">
                        <strong><?= htmlspecialchars($msg['username']) ?></strong>
                        <span><?= $msg['date_message'] ?></span>
                        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
