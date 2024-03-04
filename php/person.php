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
    $stmt = $conn->prepare("SELECT persons.*, countries.name, prizes.*, prize_details.* 
                            FROM persons 
                            LEFT JOIN countries ON persons.country_id = countries.id 
                            LEFT JOIN prizes ON persons.id = prizes.person_id
                            LEFT JOIN prize_details ON prizes.prize_detail_id = prize_details.id 
                            WHERE surname = ?");
    
    $stmt->bind_param("s", $surname);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $row = $result->fetch_assoc();

    if ($row) {
        echo "<p>it works for " . $surname . "</p>";

        // Display the fetched data
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }

    $stmt->close();
    $conn->close();
?>