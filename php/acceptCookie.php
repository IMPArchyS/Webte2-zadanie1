<?php
// Set the cookie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Set the cookie_accepted variable
    $_COOKIE['cookie_accepted'] = true;
    // Display a message
    echo "Cookie has been saved.";
}
?>