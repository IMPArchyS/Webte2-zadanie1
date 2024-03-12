<?php
// Set the cookie
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Set the cookie_accepted variable
    setcookie("cookies_accepted", true, strtotime("+30 days"), "/");
    // Display a message
    echo "Cookie has been saved.";
}
?>