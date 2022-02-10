<?php
session_start();
if (isset($_SESSION['usertype'])) {
    $userType = $_SESSION['usertype'];
}
/** @var $db */
require_once 'includes/reserveringenDB.php';

if(isset($_POST['submit'])){
    $id = mysqli_escape_string($db, $_POST['id']);
    $code = mysqli_escape_string($db, $_POST['code']);

    $codeChecker = "SELECT * FROM reserveringen WHERE id='$id' && unique_code = '$code'";
    $result = mysqli_query($db, $codeChecker);
    $reservation = mysqli_fetch_assoc($result);

    //als het id niet bestaat krijgt de gebruiker een foutmelding
    if (mysqli_num_rows($result) == 0) {
        $nonExistentId = 'Vul aub een geldige combinatie in';
    }

    if ($id != '' && $code != '' && !isset($nonExistentId)) {
        if ($reservation['unique_code'] === $code) {
            $codeTrue = true;
        }
    }
}

if (isset($_POST['delete'])){
    $id             = mysqli_escape_string($db, $_POST['id']);
    $uniqueCode     = mysqli_escape_string($db, $_POST['uniqueCode']);
    $deleteQuery    = "DELETE FROM reserveringen WHERE id = '$id' && unique_code = '$uniqueCode'";
    mysqli_query($db, $deleteQuery);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reservering</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="js/script.js"></script>
</head>
<body>
<!-- navigatie -->
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
            </li>
                <!-- stuurt je naar de pagina waar je je eigen reservering kan zien/annuleren of bewerken,als je een geldige code hebt -->
            <li class="link active">
                <a href="reservering.php">
                    <span aria-hidden="true">•</span>Uw Reservering
                </a>
            </li>
            <!-- (wordt alleen voor admin) stuurt je naar een overzicht van al de reserveringen -->
            <?php if (isset($userType)) {
            if ($userType === 'admin') { ?>
            <li class="link">
                <a href="reserveringen.php">
                    <span aria-hidden="true">•</span>Reserveringen
                </a>
                <?php } } ?>
            </li>
            <!-- checkt of er iemand is ingelogd zo ja zie je de uitlog knop zo niet zie je de link naar de login pagina -->
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
    <div class="reservationChecker">
        <?php if(!isset($codeTrue)) {?>
            <form class="inputField" action="" method="post">
                <label for="id"><?= isset($nonExistentId) ? $nonExistentId : 'id: ' ?></label>
                <input type="text" id="id" name="id" value="<?= isset($id) ? $id : '' ?>">
                <label for="code">code: </label>
                <input type="text" id="code" name="code" value="<?= isset($code) ? $code : '' ?>">

                <input type="submit" name="submit" value="bevestig">
            </form>
        <?php }?>

        <?php if (isset($reservation) && isset($codeTrue)){?>
            <table class="ReservationTable">
                <thead>
                <tr>
                    <th>Naam</th>
                    <th>Personen</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Email</th>
                    <th>Nummer</th>
                    <th>Opmerking</th>
                </tr>
                </thead>

                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($reservation['name']) ?></td>
                            <td><?= htmlspecialchars($reservation['amountOfPeople']) ?></td>
                            <!-- format date naar de Europese manier van de datum schrijven -->
                            <td><?= htmlspecialchars($newDate = date("d-m-Y",strtotime($reservation['date']))); ?></td>
                            <!-- zorgt dat alleen de uren en minuten getoond worden, als je dit niet zou doen zouden ook de seconden getoond worden -->
                            <td><?= htmlspecialchars($newTime = date("H:i", strtotime($reservation['time']))); ?></td>
                            <td><?= htmlspecialchars($reservation['email']) ?></td>
                            <td><?= htmlspecialchars($reservation['phone_number']) ?></td>
                            <td><?= htmlspecialchars($reservation['comment']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $reservation['id'] ?>&uniqueCode=<?= $reservation['unique_code'] ?>" class="hrefToButton">edit</a>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                                    <input type="hidden" name="uniqueCode" value="<?= $reservation['unique_code'] ?>">
                                    <input type="submit" name="delete" value="Annuleer" class="MakeItFit">
                                </form>
                            </td>
                        </tr>
                    </tbody>
            </table>
        <?php } ?>
    </div>



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
