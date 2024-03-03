<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <title>Nobelove Ceny</title>
</head>
<body>
<?php
    require "php/options.php";
    require "php/functions.php";
    include "php/header.php";

    $mysqli = createMySqlConnection();
    
    if ($mysqli != null) echo "<p>Connected successfully</p>";

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    echo "<p>items per page " . $itemsPerPage . "</p>";

    $offset = ($page - 1) * $itemsPerPage;
    $sql_count = "SELECT COUNT(*) as total FROM people";
    $result_count = $mysqli->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    $totalItems = $row_count['total'];
    $totalPages = ceil($totalItems / $itemsPerPage);

    $sort = isset($_GET['sort']) ? $_GET['sort'] : null; // Default sort field
    $order = isset($_GET['order']) ? $_GET['order'] : null; // Default sort order
    $year = isset($_GET['year']) ? $_GET['year'] : null; // Default year filter
    $category = isset($_GET['category']) ? $_GET['category'] : null; // Default category filter
    
    // Ensure $sort and $order contain valid values
    $allowed_sort_fields = ['surname', 'year', 'category', 'id'];
    $allowed_order_values = ['asc', 'desc'];
    
    if ($sort && !in_array($sort, $allowed_sort_fields)) {
        $sort = null;
    }
    
    if ($order && !in_array($order, $allowed_order_values)) {
        $order = null;
    }
    
    // Calculate the offset for the SQL query
    $offset = ($page - 1) * $itemsPerPage;
    
    // SQL query
    if ($sort && $order) {
        $sql = "SELECT people.surname, people.organization, countries.name AS country_name, prizes.year, prizes.category
        FROM people
        LEFT JOIN countries ON people.country_id = countries.id
        LEFT JOIN prizes ON people.id = prizes.person_id
        ORDER BY $sort $order
        LIMIT $itemsPerPage OFFSET $offset";
    } else {
        $sql = "SELECT people.surname, people.organization, countries.name AS country_name, prizes.year, prizes.category
        FROM people
        LEFT JOIN countries ON people.country_id = countries.id
        LEFT JOIN prizes ON people.id = prizes.person_id
        LIMIT $itemsPerPage OFFSET $offset";
    }

    $result = $mysqli->query($sql);
    echo "<p>Number of rows: " . $result->num_rows . "</p>";
    
    echo "<section>";
    echo "<form method='GET'>";
    createComboBoxes($mysqli, $itemsPerPage);
    echo "</form>";

    echo "<table border='1' id='nobelStuff'>";
    createTableHeader($sort, $order, $page);

    if ($result->num_rows > 0) {
        // Fetch and display data of each row
        while($row = $result->fetch_assoc()) {
            $surname = isset($row['surname']) ? $row['surname'] : '';
            $organisation = isset($row["organization"]) ? $row["organization"] : "";
            $country_name = isset($row["country_name"]) ? $row["country_name"] : "";
            $year = isset($row["year"]) ? $row["year"] : "";
            $category = isset($row["category"]) ? $row["category"] : "";

            echo "<tr>";
            echo '<td class="surname"><a href="php/person.php?surname=' . rawurlencode($surname) . '">' . htmlspecialchars($surname) . '</a></td>';
            echo "<td>" . $year . "</td>";
            echo "<td>" . $category . "</td>";
            echo "<td>" . $organisation . "</td>";
            echo "<td>" . $country_name . "</td>";
            echo "</tr>";
        }
    }

    echo "</table>";

    echo '<div class="pagination">';

    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '&sort=' . $sort . '&order=' . $order . '">Previous</a>';
    }
    
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<strong>' . $i . '</strong>';
        } else {
            echo '<a href="?page=' . $i . '&sort=' . $sort . '&order=' . $order . '">' . $i . '</a>';
        }
    }
    
    if ($page < $totalPages) {
        echo '<a href="?page=' . ($page + 1) . '&sort=' . $sort . '&order=' . $order . '">Next</a>';
    }
    
    echo '</div>';
    echo "</section>";
    // Close connection
    $mysqli->close();

    include "php/footer.php";
?>
<script src="js/sort.js"></script>
<script src="js/itemsPerPage.js"></script>
</body>
</html>