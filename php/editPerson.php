<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    echo "method : " . $method;
}