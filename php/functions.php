<?php

    function createMySqlConnection() : mysqli {
        $servername = "localhost";
        $username = "imp";
        $password = "vmko";
        $database = "nobel_prizes";
        
        // Create connection
        $mysqli = new mysqli($servername, $username, $password, $database);
        
        // Check connection
        if ($mysqli->connect_error) {
            return null;
            die("Connection failed: " . $mysqli->connect_error);
        }
        else return $mysqli;
    }

?>