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

    function createSqlQuery($sort, $order, $itemsPerPage, $offset, $year, $category) : string {
        $sql = "SELECT people.surname, people.organization, countries.name AS country_name, prizes.year, prizes.category
                FROM people
                LEFT JOIN countries ON people.country_id = countries.id
                LEFT JOIN prizes ON people.id = prizes.person_id";

        if ($year && $category) {
            $sql .= " WHERE prizes.year = '$year' AND prizes.category = '$category'";
        } elseif ($year) {
            $sql .= " WHERE prizes.year = '$year'";
        } elseif ($category) {
            $sql .= " WHERE prizes.category = '$category'";
        }

        if ($sort && $order) {
            $sql .= " ORDER BY $sort $order";
        }

        $sql .= " LIMIT $itemsPerPage OFFSET $offset";

        return $sql;
    }

    function getCount($year, $category) : int {
        $sql = "SELECT COUNT(*) AS count
                FROM people
                LEFT JOIN countries ON people.country_id = countries.id
                LEFT JOIN prizes ON people.id = prizes.person_id";

        if ($year && $category) {
            $sql .= " WHERE prizes.year = '$year' AND prizes.category = '$category'";
        } elseif ($year) {
            $sql .= " WHERE prizes.year = '$year'";
        } elseif ($category) {
            $sql .= " WHERE prizes.category = '$category'";
        }

        $result = createMySqlConnection()->query($sql);
        $count = $result->fetch_assoc()['count'];

        return $count;
    }

    function createPagination($page, $totalPages, $itemsPerPage, $sort, $order, $year, $category): void {
        echo '<div class="pagination">';

        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">Previous</a>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<strong>' . $i . '</strong>';
            } else {
                echo '<a href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">' . $i . '</a>';
            }
        }

        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">Next</a>';
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