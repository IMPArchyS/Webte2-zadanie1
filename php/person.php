<?php
    $servername = "localhost";
    $username = "imp";
    $password = "vmko";
    $dbname = "nobel_prizes";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $surname = isset($_GET['surname']) ? $_GET['surname'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM people WHERE surname = ?");
    $stmt->bind_param("s", $surname);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $row = $result->fetch_assoc();

    if ($row) {
        echo "<p>it works for " . $surname . "</p>";
    }

    $stmt->close();
    $conn->close();
?>