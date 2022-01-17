<?php
session_start();
if (isset($_SESSION['usertype'])) {
    $userType = $_SESSION['usertype'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script defer src="js/script.js"></script>
</head>

<body class="home">
<header class="primary-header flex">
    <div>
        <!-- wanneer je op het plaatje klikt wordt je terug naar de home page gestuurd -->
        <a href="home.php">
            <img src="images/palmtree2.png" alt="PLSTK logo" class="logo">
        </a>
    </div>
    <!-- de knop om de navigatie balk uit/aan te doen als je op mobiel bent -->
    <button class="mobile-nav-toggle" aria-controls="primary-navigation" aria-expanded="false">
        <span class="sr-only">Menu</span>
    </button>

    <nav>
        <ul id="primary-navigation" data-visible="false" class="primary-navigation flex">
            <!-- stuurt je naar de pagina waar je reserveringen kan plaatsen -->
            <li class="link">
                <a href="reserveren.php">
                    <span aria-hidden="true">•</span>Reserveren
                </a>
            <!-- stuurt je naar de pagina waar je je eigen reservering kan zien/annuleren of bewerken,als je een geldige code hebt -->
            <li class="link">
                <a href="reservering.php">
                    <span aria-hidden="true">•</span>Uw Reservering
                </a>
            <!-- (wordt alleen voor admin) stuurt je naar een overzicht van al de reserveringen -->
            <?php if (isset($userType)) {
                if ($userType === 'admin') { ?>
                    <li class="link">
                        <a href="reserveringen.php">
                            <span aria-hidden="true">•</span>Reserveringen
                        </a>
            <?php } } ?>

            <?php if (isset($_SESSION['username'])) {?>
                <li class="link">
                <a href="logout.php">
                    <span aria-hidden="true">•</span>Logout
                </a>
            <?php } else { ?>
                <li class="link">
                    <a href="login.php">
                        <span aria-hidden="true">•</span>Login
                    </a>
            <?php }?>
        </ul>
    </nav>
</header>
    <main>
    </main>

    <footer>

    </footer>

</body>
</html>
