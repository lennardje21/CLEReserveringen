<!-- Reserveringen plaatsen -->
<?php
    /** @var $db */
    require_once 'includes/reserveringenDB.php';


    if (isset($_POST['submit'])){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $telNummer = $_POST['phone_number'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $personen = $_POST['personen'];
        $opmerkingen = $_POST['opmerkingen'];

        $errors = [];
        if ($name == ''){
            $errors[] = 'Naam mag niet leeg zijn';
        }
        if ($email == ''){
            $errors[] = 'Email mag niet leeg zijn';
        }
        if ($date == ''){
            $errors[] = 'Datum mag niet leeg zijn';
        }
        if ($time == ''){
            $errors[] = 'Tijd mag niet leeg zijn';
        }
        if ($personen == ''){
            $errors[] = 'Personen mag niet leeg zijn';
        }

        if (empty($errors)){
            $query = "INSERT INTO reserveringen(name, email, phone_number, date, time , personen, opmerkingen)
                    VALUES ('$name', '$email', '$telNummer', '$date', '$time', '$personen', '$opmerkingen')";
            $result = mysqli_query($db, $query);

            $msg = "Beste ". $name. ",\n\nBedankt voor uw reservering op bij PLSTK op de datum/tijd:" . $date . " voor ". $personen . " personen";
            $msg = wordwrap($msg, 70);
            mail($email, "Reservering", $msg);
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserveren</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script defer src="js/script.js"></script>

</head>
<body class="BodyReserveren">
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

    <main class="reserveren">


        <form action="reserveren.php" method="post" class="inputField">
            <label for="name">Naam *</label>
            <input required type="text" name="name" id="name"
                   value="<?= isset($name) ? $name : '' ?>"/>
            <br>
            <label for="email">Email *</label>
            <input required type="email" name="email" id="email"
                   value="<?= isset($email) ? $email : '' ?>"/>
            <br>
            <label for="nummer">Telefoonnummer</label>
            <input type="text" name="phone_number" id="nummer"
                   value="<?= isset($telNummer) ? $telNummer : '' ?>"/>
            <br>
            <label for="date">Datum *</label>
            <input required type="date" name="date" id="date"
                   value="<?= isset($date) ? $date : '' ?>" min="<?= date('Y-m-d') ?>"/>
            <br>
            <label for="time">Tijd *</label>
            <input required type="time" name="time" step="3600" id="time" min="11:00" max="17:00"
                   value="<?= isset($time) ? $time : '' ?>"/>
            <br>
            <label for="personen">Personen *</label>
            <input required type="number" name="personen" id="personen"
                   value="<?= isset($personen) ? $personen : '' ?>"/>
            <br>
            <label for="opmerking">Opmerkingen</label>
            <input type="text" name="opmerkingen" id="opmerking"
                   value="<?= isset($opmerkingen) ? $opmerkingen : '' ?>"/>
            <br>
            <input type="submit" name="submit" value="Bevestig"/>
        </form>

    </main>

<footer>

</footer>

</body>
</html>
