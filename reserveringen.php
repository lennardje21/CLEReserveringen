<!-- Reserveringen in de database uitlezen -->
<?php

/** @var $db */
require_once 'includes/reserveringenDB.php';

$query = "SELECT * FROM reserveringen";
$result = mysqli_query($db, $query);

$reserveringen = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reserveringen[] = $row;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserveringen</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script defer src="js/script.js"></script>

</head>
<body>
<header>
    <p>a hidden gem not too far away</p>
</header>
<div class="navbar">
    <a href="home.php" class="logo"><img src="images/PLSTKlogo.png" alt="Bedrijfs logo PLSTK"></a>
    <div class="navbar-right">
        <div><a href="missie.php">Missie</a></div>
        <div><a href="menu.php">Menu</a></div>
        <div><a href="reserveren.php">Reserveren</a></div>
        <div><a href="vergaderen.php">Vergaderen</a></div>
    </div>
</div>

<main class="toonReserveringen">
    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Personen</th>
                <th>Datum</th>
                <th>Tijd</th>
                <th>Email</th>
                <th>Nummer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reserveringen as $reservering) {?>
                <tr>
                    <td><?= $reservering['name'] ?></td>
                    <td><?= $reservering['personen'] ?></td>
                    <td><?= $reservering['date'] ?></td>
                    <td><?= $reservering['time'] ?></td>
                    <td><?= $reservering['email'] ?></td>
                    <td><?= $reservering['phone_number'] ?></td>
                </tr>
            <?php } ?>
        </tbody>

    </table>



</main>

<footer>

</footer>

</body>
</html>