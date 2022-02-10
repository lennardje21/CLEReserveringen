<!-- Reserveringen veranderen -->
<?php
session_start();
if (isset($_SESSION['usertype'])) {
    $userType = $_SESSION['usertype'];
}
/** @var $db */
/** @var $reservation */

require_once 'includes/reserveringenDB.php';

if(!isset($_GET['id']) || $_GET['id'] === '' || !isset($_GET['uniqueCode']) || $_GET['uniqueCode'] === '') {
    // wanneer iemand fout de url aanpast wordt de gebruiker terug gestuurd naar het reservering.php scherm
    header('Location: reservering.php');
    exit;
}

if (isset($_GET['id'])){
    /* hier wordt opgehaald welke reservering er gewijzigd moet worden */
    $id= mysqli_escape_string($db,$_GET['id']);
    $uniqueCode= mysqli_escape_string($db, $_GET['uniqueCode']);

    $codeChecker = "SELECT * FROM reserveringen WHERE id='$id' && unique_code='$uniqueCode'";
    $result = mysqli_query($db, $codeChecker);
    $reservation = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 0) {
        header('Location: reservering.php');
        exit;
    }
}


if (isset($_POST['submit'])){

    $id             = $_GET['id'];
    $uniqueCode     = $_GET['uniqueCode'];
    $name           = htmlspecialchars(mysqli_escape_string($db, $_POST['name']), ENT_QUOTES);
    $email          = htmlspecialchars(mysqli_escape_string($db, $_POST['email']), ENT_QUOTES);
    $telNummer      = htmlspecialchars(mysqli_escape_string($db, $_POST['phone_number']), ENT_QUOTES);
    $date           = htmlspecialchars(mysqli_escape_string($db, $_POST['date']), ENT_QUOTES);
    $time           = htmlspecialchars(mysqli_escape_string($db, $_POST['time']), ENT_QUOTES);
    $amountOfPeople = htmlspecialchars(mysqli_escape_string($db, $_POST['personen']), ENT_QUOTES);
    $comment        = htmlspecialchars(mysqli_escape_string($db, $_POST['opmerkingen']), ENT_QUOTES);

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

    if (empty($errors)){
        if ($comment === ''){
            $comment = 'geen opmerking';
        }
        $query = "UPDATE reserveringen SET name='$name', email='$email', phone_number='$telNummer',
                         date='$date', time='$time', amountOfPeople='$amountOfPeople', comment='$comment' WHERE id = '$id' && unique_code ='$uniqueCode'";
        $result = mysqli_query($db, $query);
    }
    header('Location: reservering.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit</title>
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

<main class="reserveren">
    <form action="" method="post" class="inputField">
        <label for="name"><?= isset($errors['nameError']) ? $errors['nameError'] : 'Naam *' ?></label>
        <input type="text" name="name" id="name"
               value="<?= htmlspecialchars($reservation['name'], ENT_QUOTES) ?>"/>
        <br>
        <label for="email"><?= isset($errors['emailError']) ? $errors['emailError'] : 'Email *' ?></label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($reservation['email'], ENT_QUOTES) ?>"/>
        <br>
        <label for="nummer">Telefoonnummer</label>
        <input type="text" name="phone_number" id="nummer"
               value="<?= htmlspecialchars($reservation['phone_number'], ENT_QUOTES) ?>"/>
        <br>
        <label for="date"><?= isset($errors['dateError']) ? $errors['dateError'] : 'Datum *' ?></label>
        <input type="date" name="date" id="date"
               value="<?= htmlspecialchars($reservation['date'], ENT_QUOTES) ?>" min="<?= date('Y-m-d') ?>"/>
        <br>
        <label for="time"><?= isset($errors['timeError']) ? $errors['timeError'] : 'Tijd *' ?></label>
        <input type="time" name="time" step="3600" id="time" min="11:00" max="17:00"
               value="<?= htmlspecialchars($reservation['time'], ENT_QUOTES) ?>"/>
        <br>
        <label for="personen"><?= isset($errors['peopleError']) ? $errors['peopleError'] : 'Personen *' ?></label>
        <input type="number" name="personen" id="personen"
               value="<?= htmlspecialchars($reservation['amountOfPeople'], ENT_QUOTES) ?>"/>
        <br>
        <label for="opmerking">Opmerkingen</label>
        <input type="text" name="opmerkingen" id="opmerking"
               value="<?= htmlspecialchars($reservation['comment'], ENT_QUOTES) ?>"/>
        <br>
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
