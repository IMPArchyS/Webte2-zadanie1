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
        // default query
        $sql = "SELECT persons.surname, persons.organisation, countries.name AS country_name, prizes.year, prizes.category
                FROM persons
                LEFT JOIN countries ON persons.country_id = countries.id
                LEFT JOIN prizes ON persons.id = prizes.person_id";

        // for year & category don't display year & category based whats selected
        if ($year && $category) {
            $sql = "SELECT persons.surname, persons.organisation, countries.name AS country_name
                    FROM persons
                    LEFT JOIN countries ON persons.country_id = countries.id
                    LEFT JOIN prizes ON persons.id = prizes.person_id";      
            $sql .= " WHERE prizes.year = '$year' AND prizes.category = '$category'";
        } elseif ($year) {
            $sql = "SELECT persons.surname, persons.organisation, countries.name AS country_name, prizes.category
                    FROM persons
                    LEFT JOIN countries ON persons.country_id = countries.id
                    LEFT JOIN prizes ON persons.id = prizes.person_id";
            $sql .= " WHERE prizes.year = '$year'";
        } elseif ($category) {
            $sql = "SELECT persons.surname, persons.organisation, countries.name AS country_name, prizes.year
                    FROM persons
                    LEFT JOIN countries ON persons.country_id = countries.id
                    LEFT JOIN prizes ON persons.id = prizes.person_id";
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
                FROM persons
                LEFT JOIN countries ON persons.country_id = countries.id
                LEFT JOIN prizes ON persons.id = prizes.person_id";

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
            echo '<a class="btn btn-primary" href="?page=1&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">First</a>';
            echo '<a class="btn btn-primary" href="?page=' . ($page - 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">Previous</a>';
        }

        $startPage = max(1, $page - 2);
        $endPage = min($startPage + 4, $totalPages);

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $page) {
                echo '<strong class="btn btn-primary">' . $i . '</strong>';
            } else {
                echo '<a class="btn btn-primary" href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">' . $i . '</a>';
            }
        }

        if ($page < $totalPages) {
            echo '<a class="btn btn-primary" href="?page=' . ($page + 1) . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">Next</a>';
            echo '<a class="btn btn-primary" href="?page=' . $totalPages . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">Last</a>';
        }

        echo '</div>';
    }

    function createTableRow($row) : void {
        $surname = isset($row['surname']) ? $row['surname'] : '';
        $organisation = isset($row["organisation"]) ? $row["organisation"] : "";
        $country_name = isset($row["country_name"]) ? $row["country_name"] : "";
        $year = isset($row["year"]) ? $row["year"] : "";
        $category = isset($row["category"]) ? $row["category"] : "";

        echo "<tr>";
        echo '<td class="surname"><a href="php/person.php?surname=' . rawurlencode($surname) . '">' . htmlspecialchars($surname) . '</a></td>';

        if (!$year == "")
            echo "<td>" . $year . "</td>";

        if (!$category == "")
            echo "<td>" . $category . "</td>";

        echo "<td>" . $organisation . "</td>";
        echo "<td>" . $country_name . "</td>";
        echo "</tr>";
    }
?>