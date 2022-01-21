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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <img src="images/PLSTKimg.jpg" alt="test">
    </main>

    <div class="footer">
        <div class="openingstijden">
            <strong>openingstijden</strong>
            <p>Maandag t/m Zondag 10.00-17.00</p>
        </div>
        <div class="socials">
            <a href="https://facebook.com/plstkcafe" target="_blank"><i class="fa fa-facebook-f"></i></a>

            <a href="https://instagram.com/plstkcafe/" target="_blank"><i class="fa fa-instagram"></i></a>
        </div>
        <div class="contact">
            <strong>Contact</strong>
            <p> info@plstkcafe.nl</p>
            <p>+31 174 785 016</p>
            <p>Helmweg 7, 3151HE, Hoek van Holland</p>
        </div>
    </div>
</body>
</html>
