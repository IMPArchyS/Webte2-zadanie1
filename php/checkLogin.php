<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo 'logged_in';
} else {
    echo 'not_logged_in';
}