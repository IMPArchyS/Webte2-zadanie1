<?php

session_start();

// Uvolnenie session premennych. Tieto dva prikazy su ekvivalentne.
$_SESSION = array();
session_unset();

// Presmerovanie na hlavnu stranku.
header("location: ../index.php");
exit;