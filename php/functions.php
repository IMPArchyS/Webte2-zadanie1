<?php
namespace fnc;

    function createMySqlConnection($config) : ?\mysqli {

        $mysqli = new \mysqli($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        
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

    function getCount($year, $category, $config) : int {
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

        $result = createMySqlConnection($config)->query($sql);
        $count = $result->fetch_assoc()['count'];

        return $count;
    }

    function createPagination($page, $totalPages, $itemsPerPage, $sort, $order, $year, $category): void {
        echo '<div class="pagination m-2 d-flex justify-content-center align-items-center">';

        $startPage = max(1, $page - 2);
        $endPage = min($startPage + 4, $totalPages);

        if ($startPage > 1) {
            echo '<a class="impPag btn btn-primary" href="?page=1&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">1</a>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $page) {
                echo '<strong class="impCurrent btn btn-primary">' . $i . '</strong>';
            } else {
                echo '<a class="impPag btn btn-primary" href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">' . $i . '</a>';
            }
        }

        if ($endPage < $totalPages) {
            echo '<a class="impPag btn btn-primary" href="?page=' . $totalPages . '&itemsPerPage=' . $itemsPerPage . '&sort=' . $sort . '&order=' . $order . '&year=' . $year . '&category=' . $category . '">' . $totalPages . '</a>';
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

    /// USER MANAGEMENT :
    
    function createUser($firstName, $lastName, $email, $hashedPassword, $userSecret, $codeURL, $config) {
        // Database connection details
        $conn = createMySqlConnection($config);
    
        // Prepare the SQL statement
        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, 2fa_code) VALUES (?, ?, ?, ?, ?)');
    
        // Bind the parameters to the SQL statement
        $stmt->bind_param('sssss', $firstName, $lastName, $email, $hashedPassword, $userSecret);
    
        // Execute the SQL statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function getUserByEmail($email, $config) {
        // Database connection details
        $conn = createMySqlConnection($config);
    
        // Prepare the SQL statement
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    
        // Bind the email to the SQL statement
        $stmt->bind_param('s', $email);
    
        // Execute the SQL statement
        $stmt->execute();
    
        // Get the result of the query
        $result = $stmt->get_result();
    
        // Fetch the user data
        $user = $result->fetch_assoc();
    
        return $user;
    }