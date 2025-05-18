<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Noter ce livre</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #fdf6e3;
            font-family: 'Segoe UI', sans-serif;
            color: #382110;
        }

        .noter-container {
            max-width: 600px;
            margin: 100px auto 60px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            text-align: center;
        }

        .noter-container h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .noter-container p {
            color: #666;
            margin-bottom: 30px;
        }

        .rating {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 32px;
            color: #ccc;
            cursor: pointer;
            transition: transform 0.2s, color 0.2s;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: #f7b731;
            transform: scale(1.2);
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            resize: vertical;
        }

        button {
            background-color: #382110;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5b3c1e;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="noter-container">
    <h1>Noter ce livre</h1>
    <p>Quelle est votre appréciation ? Laissez un commentaire !</p>

    <form action="noter_livre.php" method="POST">
        <input type="hidden" name="livre_id" value="<?= isset($_GET['livre_id']) ? intval($_GET['livre_id']) : '' ?>">

        <div class="rating">
            <input type="radio" id="star5" name="note" value="5"><label for="star5">★</label>
            <input type="radio" id="star4" name="note" value="4"><label for="star4">★</label>
            <input type="radio" id="star3" name="note" value="3"><label for="star3">★</label>
            <input type="radio" id="star2" name="note" value="2"><label for="star2">★</label>
            <input type="radio" id="star1" name="note" value="1"><label for="star1">★</label>
        </div>

        <textarea name="commentaire" placeholder="Partagez votre avis..."></textarea>

        <button type="submit">Envoyer</button>
    </form>
</div>

</body>
</html>
