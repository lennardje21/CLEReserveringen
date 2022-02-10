<!-- Reserveringen plaatsen -->
<?php
session_start();
if (isset($_SESSION['usertype'])) {
    $userType = $_SESSION['usertype'];
}

/** @var $db */
require_once 'includes/reserveringenDB.php';

if (isset($_POST['submit'])) {
    //ik gebruik hier mysqli_escape_string om eventuele SQL injections op te vangen
    /* Ook maak ik gebruik van htmlspecialchars om xss op te vangen en te voorkomen,
    door deze tag worden speciale karakters omgezet naar iets anders waardoor ze niet meer dezelfde functie hebben */
    $name               = htmlspecialchars(mysqli_escape_string($db, $_POST['name']), ENT_COMPAT);
    $email              = htmlspecialchars(mysqli_escape_string($db, $_POST['email']), ENT_COMPAT);
    $telNummer          = htmlspecialchars(mysqli_escape_string($db, $_POST['phone_number']), ENT_COMPAT);
    $date               = htmlspecialchars(mysqli_escape_string($db, $_POST['date']), ENT_COMPAT);
    $time               = htmlspecialchars(mysqli_escape_string($db, $_POST['time']), ENT_COMPAT);
    $amountOfPeople     = htmlspecialchars(mysqli_escape_string($db, $_POST['amountOfPeople']), ENT_COMPAT);
    $comment            = htmlspecialchars(mysqli_escape_string($db, $_POST['comment']), ENT_COMPAT);
    $uniqueCode         = htmlspecialchars(mysqli_escape_string($db, $_POST['unique_code']), ENT_COMPAT);

    $errors = [];
    if ($name == '') {
        $errors['nameError'] = 'Naam mag niet leeg zijn';
    }
    if ($email == '') {
        $errors['emailError'] = 'Email mag niet leeg zijn';
    }
    if ($date == '') {
        $errors['dateError'] = 'Datum mag niet leeg zijn';
    }
    if ($time == '') {
        $errors['timeError'] = 'Tijd mag niet leeg zijn';
    }
    if ($amountOfPeople == '') {
        $errors['peopleError'] = 'Personen mag niet leeg zijn';
    }

    if (empty($errors)) {
        if ($comment === '') {
            $comment = 'geen opmerking';
        }

        $query = "INSERT INTO reserveringen(name, email, phone_number, date, time , amountOfPeople, comment, unique_code)
                VALUES ('$name', '$email', '$telNummer', '$date', '$time', '$amountOfPeople', '$comment', '$uniqueCode')";
        $result = mysqli_query($db, $query);

        $query2 = "SELECT MAX(`id`) as id from reserveringen";
        $checkId = mysqli_query($db, $query2);
        $currentId = mysqli_fetch_assoc($checkId);
        $id = $currentId['id'];
        $reservationPlaced = true;
    }
}

// creëert een random code met een lengte van 10 character
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reserveren</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <li class="link active">
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
    <main class="reserveren">
        <form action="reserveren.php" method="post" class="inputField">
            <label for="name"><?= isset($errors['nameError']) ? $errors['nameError'] : 'Naam' ?></label>
            <input type="text" name="name" id="name"
                   value="<?= isset($name) ? htmlspecialchars($name, ENT_COMPAT) : '' ?>"/>
            <!-- de code bij value zorgt ervoor dat als je al eens iets hebt ingevuld dit onthoudt wordt -->
            <br>
            <label for="email"><?= isset($errors['emailError']) ? $errors['emailError'] : 'Email' ?></label>
            <input type="email" name="email" id="email"
                   value="<?= isset($email) ? htmlspecialchars($email, ENT_COMPAT) : '' ?>"/>
            <br>
            <label for="nummer">Telefoonnummer</label>
            <input type="text" name="phone_number" id="nummer"
                   value="<?= isset($telNummer) ? htmlspecialchars($telNummer, ENT_COMPAT) : '' ?>"/>
            <br>
            <label for="date"><?= isset($errors['dateError']) ? $errors['dateError'] : 'Datum' ?></label>
            <input type="date" name="date" id="date"
                   value="<?= isset($date) ? htmlspecialchars($date, ENT_COMPAT) : '' ?>" min="<?= date('Y-m-d') ?>"/>
            <br>
            <label for="time"><?= isset($errors['timeError']) ? $errors['timeError'] : 'Tijd' ?></label>
            <input type="time" name="time" step="3600" id="time" min="11:00" max="17:00"
                   value="<?= isset($time) ? htmlspecialchars($time, ENT_COMPAT) : '' ?>"/>
            <br>
            <label for="amountOfPeople"><?= isset($errors['peopleError']) ? $errors['peopleError'] : 'Personen' ?></label>
            <input type="number" name="amountOfPeople" id="amountOfPeople"
                   value="<?= isset($amountOfPeople) ? htmlspecialchars($amountOfPeople, ENT_COMPAT) : '' ?>"/>
            <br>
            <label for="comment">Opmerkingen</label>
            <input type="text" name="comment" id="comment"
                   value="<?= isset($comment) ? htmlspecialchars($comment, ENT_COMPAT) : '' ?>"/>
            <br>
            <!-- Hier wordt de functie generateRandomString aangeroepen  -->
            <input type="hidden" name="unique_code" value="<?= generateRandomString() ?>"/>
            <input type="submit" name="submit" value="Bevestig"/>

        </form>
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
