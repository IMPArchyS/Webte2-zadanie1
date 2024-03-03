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

    function createSqlQuery($sort, $order, $itemsPerPage, $offset) : string {
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
        return $sql;
    }

    function createPagination ($page, $totalPages, $itemsPerPage, $sort, $order) : void {
        echo '<div class="pagination">';

        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '">Previous</a>';
        }
        
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<strong>' . $i . '</strong>';
            } else {
                echo '<a href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '">' . $i . '</a>';
            }
        }
        
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '">Next</a>';
        }
        
        echo '</div>';
    }

    function createTableRow($row) : void {
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
?>