<!-- Reserveringen in de database uitlezen -->
<?php
session_start();

/* Checkt of de gebruiker de juiste rechten heeft om deze pagina te kunnen zien */
if (isset($_SESSION)) {
    $userType = $_SESSION['usertype'];
    if ($userType !== 'admin'){
        header("Location: home.php");
        exit;
    }
}

/** @var $db */
/** @var $reservations */


require_once 'includes/reserveringenDB.php';


if (isset($_POST['delete'])){
    $id = mysqli_escape_string($db, $_POST['id']);
    $deleteQuery = "DELETE FROM reserveringen WHERE `id`='$id'";
    mysqli_query($db, $deleteQuery);
    mysqli_close($db);
} else if (isset($_POST['submit'])){
    $date = mysqli_escape_string($db, $_POST['date']);
    $query = "SELECT * FROM reserveringen WHERE `date`='$date'";
    $result = mysqli_query($db, $query);

    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    mysqli_close($db);
} else {
    $date = date("Y-m-d");
    $query = "SELECT * FROM reserveringen WHERE `date`='$date'";
    $result = mysqli_query($db, $query);

    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    mysqli_close($db);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reserveringen</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="js/script.js"></script>

</head>
<body>

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
            <li class="link active">
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

<main class="toonReserveringen">
    <form action="" method="post" id="inputDate">
        <label for="date">Van welke dag wil je reserveringen zien</label><br>
        <input type="date" name="date" id="date" value="<?php if (isset($_POST['date'])){echo $_POST['date'];} else{ echo date("d-m-Y");}  ?>"><br>

        <input type="submit" name="submit" value="bevestig">
    </form>

    <?php if (isset($_POST['id'])) {?>

    <?php }?>

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
        <?php if (isset($reservations)){?>
            <tbody>
                <?php foreach($reservations as $reservation) {?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['name']) ?></td>
                        <td><?= htmlspecialchars($reservation['amountOfPeople']) ?></td>
                        <td><?= htmlspecialchars($newDate = date("d-m-Y",strtotime($reservation['date']))); ?></td>
                        <td><?= htmlspecialchars($newTime = date("H:i", strtotime($reservation['time']))); ?></td>
                        <td><?= htmlspecialchars($reservation['email']) ?></td>
                        <td><?= htmlspecialchars($reservation['phone_number']) ?></td>
                        <td><?= htmlspecialchars($reservation['comment']) ?></td>
                        <td>
                            <form action="" method="post">
                                <button id="editBtn" value="<?= $reservation['id'] ?>">Edit</button>

                            </form>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                                <input type="submit" name="delete" value="delete" class="MakeItFit">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>

    </table>




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