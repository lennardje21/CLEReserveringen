<?php
session_start();

//logout
session_destroy();

header("Location: login.php");
exit;