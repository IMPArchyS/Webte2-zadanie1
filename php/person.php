<?php
    session_start();
    require "options.php";

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
    ?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title><?php echo isset($row['surname']) ? $row['surname'] : 'Nobel Winner'; ?></title>
</head>
<body>
    
<?php
    include "header.php";

    if ($row) {
        echo "<p>it works for " . $surname . "</p>";

        // Display the fetched data
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }

    include "footer.php";

    $stmt->close();
    $conn->close();
?>
</body>
<script>
    // Fetch the button with id #logout
    var logoutButton = $('#user-logout');

    // Add a click event listener to the button
    logoutButton.on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'logout.php',
            success: function () {
                window.location.href = '/';
            },
        });
    });
</script>
</html>
