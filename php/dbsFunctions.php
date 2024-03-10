<?php
namespace dbs;
require_once "functions.php";
require_once "../config.php";

// Function to get the country id by name returns null if no country is found
function getCountryIdByName($countryName, $config) {
    $conn = \fnc\createMySqlConnection($config);
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

// Function to check if a person exists in the database if not returns false, returns null if connection error
function personExists($name, $surname, $birth, $config) {
    $conn = \fnc\createMySqlConnection($config);
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
    $conn = \fnc\createMySqlConnection($config);
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

// Function to create a new country in the countries table returns null if cant create
function createCountry($countryName, $config) {
    $conn = \fnc\createMySqlConnection($config);
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

// returns id of prize_details based on language and genre
function getPrizeDetails($language_en, $language_sk, $genre_en, $genre_sk, $config) {
    $conn = \fnc\createMySqlConnection($config);
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

function getEntryById($id, $table, $config) {
    $conn = \fnc\createMySqlConnection($config);
    if (!$conn) return null;

    $stmt = $conn->prepare(getSQLbyEntry($table));
    if (!$stmt) { $conn->close(); return null; }

    $stmt->bind_param("s", $id);

    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        return null;
    }
    $result = $stmt->get_result();
    $entry = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
    
    return $entry;
}

function getSQLbyEntry($entryType) {
    switch ($entryType) {
        case 'persons':
            return "SELECT * FROM persons WHERE id = ?";

        case 'countries':
            return "SELECT * FROM countries WHERE id = ?";

        case 'prize_details':
            return "SELECT pd.id, pd.language_en, pd.language_sk, pd.genre_en, pd.genre_sk FROM prize_details AS pd
            LEFT JOIN prizes ON pd.id = prize_detail_id
            WHERE pd.id = ?";

        case 'prizes':
            return "SELECT * FROM prizes WHERE person_id = ?";

        default:
            return "";
    }
}
/*
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
*/
function checkInfo($info) {
    if (strlen($info["name"]) > 254) return false;
    if (strlen($info["surname"]) > 254) return false;
    if (strlen($info["org"]) > 254) return false;
    if ((int)($info["birth"]) < 0 || (int)($info["birth"]) > 9999) return false;
    if ((int)($info["death"]) < 0 || (int)($info["death"]) > 9999) return false;

    if ((int)($info["year"]) < 0 || (int)($info["year"]) > 9999) return false;
    if (strlen($info["category"]) > 254) return false;

    if (strlen($info["language_en"]) > 254) return false;
    if (strlen($info["language_sk"]) > 254) return false;
    if (strlen($info["genre_en"]) > 254) return false;
    if (strlen($info["genre_sk"]) > 254) return false;

    return true;
}