<?php
session_start();

/** @var $db */
require_once 'includes/reserveringenDB.php';
/* login functie */
if (isset($_POST['login'])) {
    /* haalt de login data op en checkt op sql injection */
    $loginUsername = mysqli_escape_string($db, $_POST['username']);
    $loginPassword = mysqli_escape_string($db, $_POST['password']);

    /* checkt of de ingevulde username bestaat */
    $accountChecker = "SELECT * FROM accounts Where username='$loginUsername'";
    $accountCheckResult = mysqli_query($db, $accountChecker);
    $userNameExist = mysqli_num_rows($accountCheckResult);
    if ($userNameExist === 0){
        /* foutmelding als de username niet bekend is */
        $wrongCombo = 'De combinatie van wachtwoord en username is niet bij ons bekend';
    } else {
        /* als de username bestaat wordt er gekeken of het wachtwoord erbij klopt */
        $account = mysqli_fetch_assoc($accountCheckResult);
        $passwordCheck = password_verify($loginPassword, $account['password']);

        if ($passwordCheck === true) {
            /* wachtwoord en username kloppen samen dan wordt er ingelogd */
            $_SESSION['username'] = $loginUsername;
            $_SESSION['usertype'] = $account['userType'];
            /* stuurt je terug naar de home page nadat je bent ingelogd */
            header("Location: home.php");
            exit;

        } else {
            /* foute combi van wachtwoord en username, gebruiker krijgt een foutmelding */
            $wrongCombo = 'De combinatie van wachtwoord en username is niet bij ons bekend';
        }
    }

}

if (isset($_POST['submit'])){
    /* haalt de ingevulde data op en checkt op sql injection */
    $registerUsername       = mysqli_escape_string($db, $_POST['registerUsername']);
    $registerEmail          = mysqli_escape_string($db, $_POST['registerEmail']);
    $registerPassword       = mysqli_escape_string($db, $_POST['registerPassword']);
    $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);

    /* checkt of de ingevulde username uniek is */
    $usernameChecker = "SELECT * FROM accounts WHERE username='$registerUsername'";
    $usernameResult = mysqli_query($db, $usernameChecker);

    if (mysqli_num_rows($usernameResult) > 0) {
        $uniqueUsername = "Deze username is al in gebruik";
    }

    /* checkt of het ingevulde email adres uniek is */
    $emailChecker = "SELECT * FROM accounts WHERE email='$registerEmail'";
    $emailResult  = mysqli_query($db, $emailChecker);

    if (mysqli_num_rows($emailResult) > 0) {
        $uniqueEmail = "Dit email adres is al in gebruik";
    }

    /* checkt of alles is ingevuld */
    $errors = [];
    if ($registerUsername == '' || mysqli_num_rows($usernameResult) != 0){
        $errors[] = 'Naam mag niet leeg zijn';
    }
    if ($registerEmail == '' || mysqli_num_rows($emailResult) != 0){
        $errors[] = 'Email mag niet leeg zijn';
    }
    if ($registerPassword == ''){
        $errors[] = 'Datum mag niet leeg zijn';
    }

    if (empty($errors)){
        /* voegt een nieuw account toe aan de accounts database */
        $query = "INSERT INTO accounts (username, email, password)
                    VALUES ('$registerUsername', '$registerEmail', '$hashedPassword')";
        $result = mysqli_query($db, $query);
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script defer src="js/script.js"></script>
</head>
<body class="BodyReserveren">
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
            <?php if (isset($_SESSION['usertype'])) {
                if ($_SESSION['usertype'] === 'admin') { ?>
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
                <li class="link active">
                    <a href="login.php">
                        <span aria-hidden="true">•</span>Login
                    </a>
            <?php }?>
        </ul>
    </nav>
</header>

<main>
    <button id="openModal">Register</button>

    <div class="loginOrRegister">
        <div class="loginForm">
            <form action="" method="post" class="login">
                <?php if (isset($wrongCombo)){
                    echo $wrongCombo;
                } ?>
                <br>
                <label for="username">Username:</label>
                <input required type="text" name="username" id="username" value="<?= isset($loginUsername) ? $loginUsername : ''?>">

                <label for="password">Password:</label>
                <input required type="password" name="password" id="password" value="<?= isset($loginPassword) ? $loginPassword : ''?>">

                <input type="submit" name="login" id="login" value="login">
            </form>
        </div>


        <div class="registerModal" id="PlsModal">
            <!-- Hier worden nieuwe accounts aangemaakt -->
            <div class="accountRegister">
                <form action="" method="post" class="inputField" id="registerForm">
                    <!-- hier moet je een username invullen, hier zal je later mee inloggen -->
                    <label for="registerUsername"><?= isset($uniqueUsername) ? $uniqueUsername : 'Username' ?></label>
                    <input type="text" name="registerUsername" id="registerUsername" required
                           value="<?= isset($registerUsername) ? $registerUsername : '' ?>"/>
                    <br>
                    <!-- Hier moet een email adres worden ingevuld -->
                    <label for="registerEmail"><?= isset($uniqueEmail) ? $uniqueEmail : 'Email' ?></label>
                    <input type="email" name="registerEmail" id="registerEmail" required
                           value="<?= isset($registerEmail) ? $registerEmail : '' ?>"/>
                    <br>
                    <!-- Hier wordt een wachtwoord ingevuld, deze wordt later gehashed -->
                    <label for="registerPassword">Wachtwoord</label>
                    <input type="password" name="registerPassword" id="registerPassword" required/>
                    <br>
                    <input type="submit" name="submit" value="Bevestig"/>
                </form>
            </div>
        </div>
    </div>
</main>

<footer>

</footer>

</body>
</html>