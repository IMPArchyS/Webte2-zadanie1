<?php
require_once "../config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    $result = [
        'name' => $_POST['name'],
        'surname' => $_POST['surname'],
        'sex' => $_POST['sex'],
        'org' => $_POST['org'],
        'birth' => $_POST['birth'],
        'death' => $_POST['death'],
        'country' => $_POST['country'],
        'year' => $_POST['year'],
        'category' => $_POST['category'],
        'contribution_en' => $_POST['contribution_en'],
        'contribution_sk' => $_POST['contribution_sk'],
        'language_en' => $_POST['language_en'],
        'language_sk' => $_POST['language_sk'],
        'genre_en' => $_POST['genre_en'],
        'genre_sk' => $_POST['genre_sk']
    ];
        
    switch (insertPersonIntoDB($result, $dbconfig)) {
        case 0:
            echo "person exists";
            break;
        case 2:
            echo "person insertion error";
            break;
        case 2:
            echo "newly person doesnt exist";
            break;
        case 3:
            echo "prize detail error";
            break;
        case 4:
            echo "prize insert error";
            break;
        case 5:
            echo "success";
            break;
    }
}


function insertPersonIntoDB($information, $config) {
    $conn = fnc\createMySqlConnection($config);
    $conn->begin_transaction();

    // Check if the country exists in the database
    $countryId = getCountryIdByName($information["country"], $config);
    if (!$countryId) {
        $countryId = createCountry($information["country"], $config);
    }

    // Check if the person already exists in the database
    if (personExists($information["name"], $information["surname"], $information["birth"], $config)) {
        $conn->rollback();
        $conn->close();
        return 0;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO persons (name, surname, organisation, sex, birth, death, country_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $birth = isset($information["birth"]) && $information["birth"] !== "" ? (int)$information["birth"] : 0;
    $death = isset($information["death"]) && $information["death"] !== "" ? (int)$information["death"] : 0;
    $sex = substr($information["sex"], 0, 1);
    
    $stmt->bind_param("sssssss", $information["name"], $information["surname"], $information["organisation"], $sex, $birth, $death, $countryId);

    // Execute the statement
    if (!$stmt->execute()) {
        $conn->rollback();
        $stmt->close();
        $conn->close();
        return 1;
    }

    $personId = $conn->insert_id;
    if (!$personId) {
        $conn->rollback();
        $stmt->close();
        $conn->close();
        return 2;
    }
    $prizeDetailId = null;
    if ($information["category"] == "Literature") {
        $prizeDetailId = getPrizeDetails($information["language_en"], $information["language_sk"], $information["genre_en"], $information["genre_sk"], $config);
        if (!$prizeDetailId) {
            $stmt = $conn->prepare("INSERT INTO prize_details (language_en, language_sk, genre_en, genre_sk) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $information["language_en"], $information["language_sk"], $information["genre_en"], $information["genre_sk"]);
            
            if (!$stmt->execute()) {
                $conn->rollback();
                $stmt->close();
                $conn->close();
                return 3;
            }
            $prizeDetailId = $conn->insert_id;
        }
    }
    $categoryEN = $information["category"];
    $categorySK = convertCategory($categoryEN);
    

    $stmt = $conn->prepare("INSERT INTO prizes (year, category, contribution_en, contribution_sk, person_id, prize_detail_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $information["year"], $categorySK, $information["contribution_en"], $information["contribution_sk"], $personId, $prizeDetailId);

    if (!$stmt->execute()) {
        $conn->rollback();
        $stmt->close();
        $conn->close();
        return 4;
    }

    $conn->commit();
    $stmt->close();
    $conn->close();
    return 5;
}

// Function to get the country id by name returns null if no country is found
function getCountryIdByName($countryName, $config) {
        $conn = fnc\createMySqlConnection($config);
        if (!$conn) return null;

        $stmt = $conn->prepare("SELECT id FROM countries WHERE name = ?");
        if (!$stmt) { $conn->close(); return null;}

        $stmt->bind_param("s", $countryName);

        if (!$stmt->execute()) {
            $stmt->close(); 
            $conn->close(); 
            return null;
        }
        $stmt->bind_result($countryId);
        $stmt->fetch();

        $stmt->close();
        $conn->close();
        return $countryId ? $countryId : null;
}

// Function to create a new country in the countries table returns null if cant create
function createCountry($countryName, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return null;

    $stmt = $conn->prepare("INSERT INTO countries (name) VALUES (?)");
    if (!$stmt) { $conn->close(); return null;}

    $stmt->bind_param("s", $countryName);

    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return null;
    }
    $countryId = $stmt->insert_id;

    $stmt->close();
    $conn->close();
    return $countryId;
}

// Function to check if a person exists in the database if not returns false, returns null if connection error
function personExists($name, $surname, $birth, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return null;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM persons WHERE name = ? AND surname = ? AND birth = ?");
    if (!$stmt) { $conn->close(); return null; }

    $stmt->bind_param("sss", $name, $surname, $birth);

    if (!$stmt->execute()) {
        $stmt->close();             
        $conn->close();
        return null;
    }
    $stmt->bind_result($count);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    if ($count > 0)
        return true;

    return false;
}

// returns id of person based on name, surname, birth, returns null if error
function getPersonByCreds($name, $surname, $birth, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return null;

    $stmt = $conn->prepare("SELECT id FROM persons WHERE name = ? AND surname = ? AND birth = ?");
    if (!$stmt) { $conn->close(); return null; }

    $stmt->bind_param("sss", $name, $surname, $birth);

    if (!$stmt->execute()) {
        $stmt->close();             
        $conn->close();
        return null;
    }
    $stmt->bind_result($personId);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $personId ? $personId : null;
}
// returns id of prize_details based on language and genre
function getPrizeDetails($language_en, $language_sk, $genre_en, $genre_sk, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return null;

    $stmt = $conn->prepare("SELECT id FROM prize_details WHERE language_en = ? AND language_sk = ? AND genre_en = ? AND genre_sk = ?");
    if (!$stmt) { $conn->close(); return null; }

    $stmt->bind_param("ssss", $language_en, $language_sk, $genre_en, $genre_sk);

    if (!$stmt->execute()) {
        $stmt->close();             
        $conn->close();
        return null;
    }
    $stmt->bind_result($prizeDetailId);
    $stmt->fetch();

    $stmt->close();
    $conn->close();

    return $prizeDetailId ? $prizeDetailId : null;
}

function convertCategory($category) {
    $slovakCategory = "";
    switch ($category) {
        case 'Physics':
            $slovakCategory = 'fyzika';
            break;
        case 'Chemistry':
            $slovakCategory = 'chémia';
            break;
        case 'Medicine':
            $slovakCategory = 'medicína';
            break;
        case 'Literature':
            $slovakCategory = 'literatúra';
            break;
        case 'Peace':
            $slovakCategory = 'mier';
            break;
        default:
            $slovakCategory = '';
            break;
    }
    return $slovakCategory;
}