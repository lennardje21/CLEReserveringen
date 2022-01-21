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
    $name           = mysqli_escape_string($db, $_POST['name']);
    $email          = mysqli_escape_string($db, $_POST['email']);
    $telNummer      = mysqli_escape_string($db, $_POST['phone_number']);
    $date           = mysqli_escape_string($db, $_POST['date']);
    $time           = mysqli_escape_string($db, $_POST['time']);
    $amountOfPeople = mysqli_escape_string($db, $_POST['personen']);
    $comment        = mysqli_escape_string($db, $_POST['opmerkingen']);

    $errors = [];
    if ($name === ''){
        $errors[] = 'Naam mag niet leeg zijn';
    }
    if ($email === ''){
        $errors[] = 'Email mag niet leeg zijn';
    }
    if ($date === ''){
        $errors[] = 'Datum mag niet leeg zijn';
    }
    if ($time === ''){
        $errors[] = 'Tijd mag niet leeg zijn';
    }
    if ($amountOfPeople === ''){
        $errors[] = 'Personen mag niet leeg zijn';
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
        <label for="name">Naam *</label>
        <input required type="text" name="name" id="name"
               value="<?= $reservation['name'] ?>"/>
        <br>
        <label for="email">Email *</label>
        <input required type="email" name="email" id="email"
               value="<?= $reservation['email'] ?>"/>
        <br>
        <label for="nummer">Telefoonnummer</label>
        <input type="text" name="phone_number" id="nummer"
               value="<?= $reservation['phone_number'] ?>"/>
        <br>
        <label for="date">Datum *</label>
        <input required type="date" name="date" id="date"
               value="<?= $reservation['date'] ?>" min="<?= date('Y-m-d') ?>"/>
        <br>
        <label for="time">Tijd *</label>
        <input required type="time" name="time" step="3600" id="time" min="11:00" max="17:00"
               value="<?= $reservation['time'] ?>"/>
        <br>
        <label for="personen">Personen *</label>
        <input required type="number" name="personen" id="personen"
               value="<?= $reservation['amountOfPeople'] ?>"/>
        <br>
        <label for="opmerking">Opmerkingen</label>
        <input type="text" name="opmerkingen" id="opmerking"
               value="<?= $reservation['comment'] ?>"/>
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
