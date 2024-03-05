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
    require "php/options.php";
    require "php/functions.php";
    include "php/header.php";


    /// connect to DB
    $mysqli = createMySqlConnection();

    echo '<a href="php/restricted.php">Restricted Page</a>';
    //createPersonButtons();
    /// filters
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
    // sort fields
    $sort = isset($_GET['sort']) ? $_GET['sort'] : null; // Default sort field
    $order = isset($_GET['order']) ? $_GET['order'] : null; // Default sort order
    $year = isset($_GET['year']) ? $_GET['year'] : null; // Default year filter
    $category = isset($_GET['category']) ? $_GET['category'] : null; // Default category filter
    
    $offset = ($page - 1) * $itemsPerPage;
    $sql_count = "SELECT COUNT(*) as total FROM persons";
    $result_count = $mysqli->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    //$totalItems = $row_count['total'];
    $totalItems = getCount($year, $category);
    $totalPages = ceil($totalItems / $itemsPerPage);

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
    $sql = createSqlQuery($sort, $order, $itemsPerPage, $offset, $year, $category);
    $result = $mysqli->query($sql);
    
    /// table
    echo "<section>";
    createComboBoxes($mysqli, $itemsPerPage, $year, $category);
    echo "<table class='table table-bordered table-striped' id='nobelStuff'>";
    createTableHeader($sort, $order, $page, $itemsPerPage, $year, $category);

    if ($result->num_rows > 0) {
        // Fetch and display data of each row
        while($row = $result->fetch_assoc()) {
            createTableRow($row);
        }
    }
    echo "</table>";
    createPagination($page, $totalPages, $itemsPerPage, $sort, $order, $year, $category);
    echo "</section>";

    /// Close connection
    $mysqli->close();
    include "php/footer.php";
?>
<script src="js/sort.js"></script>
<script src="js/yearsSort.js"></script>
<script src="js/categorySort.js"></script>
<script src="js/itemsPerPage.js"></script>
<script src="js/util.js"></script>
</body>
</html>