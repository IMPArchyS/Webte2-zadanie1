<?php
    session_start();
    require_once "options.php";
    require_once "functions.php";
    require_once "../config.php";

    // Create connection
    $conn = fnc\createMySqlConnection($dbconfig);

    $surname = isset($_GET['surname']) ? $_GET['surname'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("SELECT persons.*, countries.name as countryName, prizes.*, prize_details.* 
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title><?php echo isset($row['surname']) ? $row['surname'] : 'Nobel Winner'; ?></title>
</head>
<body>

<?php
    include_once "header.php";

    if ($row) {
        echo "<p>it works for " . $surname . "</p>";

        // Display the fetched data
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        createPersonButtons();
    }
    include_once "footer.php";

    $stmt->close();
    $conn->close();
?>
<div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong id="toastInfo" class="mr-auto">Feedback</strong>
    </div>
    <div id="feedbackToastbody" class="toast-body">
        <p id="feedbackToastText"></p>
    </div>
</div>  
</body>
<script src="../js/person.js"></script>
<script>
    $("#user-logout").on("click", function () {
        window.location.href = "logout.php";
    });
</script>
</html>
