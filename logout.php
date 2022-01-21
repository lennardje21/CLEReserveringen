<?php
session_start();

//logout
session_unset();
session_destroy();

header("Location: login.php");
exit;