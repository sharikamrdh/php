<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Header commun -->
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<header>
    <div class="logo">Fable</div>

    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="search.php">Recherche</a></li>
            <li><a href="club.php">Clubs</a></li>
            <li><a href="blog.php">Blog</a></li>
        </ul>
    </nav>

    <div class="icons">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php" title="Mon profil"><i class="fas fa-user" id="user-icon"></i></a>
        <?php else: ?>
            <a class="login-btn" href="login.html">Sign In</a>
            <a class="signup-btn" href="signup.html">Sign Up</a>
        <?php endif; ?>
    </div>
</header>


