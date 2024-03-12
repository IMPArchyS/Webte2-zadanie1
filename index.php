<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <title>Nobelove Ceny</title>
</head>
<body>
<?php
    session_start();
    require_once "php/options.php";
    require_once "php/functions.php";
    include_once "php/header.php";
    include_once "config.php";

    if (!isset($_COOKIE["cookies_accepted"])) {
        include_once "php/cookies.php";
    }

    /// connect to DB
    $mysqli = fnc\createMySqlConnection($dbconfig);

    echo "<main class='container impContainer'>";
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    } else {
        echo '<a class="impButton btn btn-primary mx-0" href="php/restricted.php">Pridať Výťaza</a>';
    }
    /// filters
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
    // sort fields
    $sort = isset($_GET['sort']) ? $_GET['sort'] : null; // Default sort field
    $order = isset($_GET['order']) ? $_GET['order'] : null; // Default sort order
    $year = isset($_GET['year']) ? $_GET['year'] : null; // Default year filter
    $category = isset($_GET['category']) ? $_GET['category'] : null; // Default category filter

    $sql_count = "SELECT COUNT(*) as total FROM persons";
    $totalItems = fnc\getCount($year, $category, $dbconfig);
    if ($itemsPerPage === 'all') $itemsPerPage = $totalItems;
    $totalPages = ceil($totalItems / $itemsPerPage);
    
    $offset = ($page - 1) * $itemsPerPage;
    $result_count = $mysqli->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    //$totalItems = $row_count['total'];

    // Ensure $sort and $order contain valid values
    $allowed_sort_fields = ['surname', 'year', 'category'];
    $allowed_order_values = ['asc', 'desc'];
    
    if ($sort && !in_array($sort, $allowed_sort_fields)) {
        $sort = null;
    }    
    if ($order && !in_array($order, $allowed_order_values)) {
        $order = null;
    }
    // Calculate the offset for the SQL query
    $offset = ($page - 1) * $itemsPerPage;
    
    /// SQL query
    $sql = fnc\createSqlQuery($sort, $order, $itemsPerPage, $offset, $year, $category);
    $result = $mysqli->query($sql);
    
    /// table
    echo "<section>";
    createComboBoxes($mysqli, $itemsPerPage, $year, $category);
    echo "<table class='table table-bordered table-striped table-dark my-4' id='nobelStuff'>";
    createTableHeader($sort, $order, $page, $itemsPerPage, $year, $category);

    if ($result->num_rows > 0) {
        // Fetch and display data of each row
        while($row = $result->fetch_assoc()) {
            fnc\createTableRow($row);
        }
    }
    echo "</table>";
    fnc\createPagination($page, $totalPages, $itemsPerPage, $sort, $order, $year, $category);
    echo "</section>";
    echo "</main>";


    /// Close connection
    $mysqli->close();
    include_once "php/footer.php";
?>
<script src="js/sort.js"></script>
<script src="js/yearsSort.js"></script>
<script src="js/categorySort.js"></script>
<script src="js/itemsPerPage.js"></script>
<script src="js/util.js"></script>
</body>
</html>