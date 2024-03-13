<?php
require_once "../config.php";
require_once "functions.php";
require_once "dbsFunctions.php";

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

    if (!dbs\checkByRegexForAdding($result)) {
        echo "fail";
        exit();
    }

    if (!dbs\checkInfo($result)) {
        echo "fail";
        exit();
    }
    
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
    $countryId = dbs\getCountryIdByName($information["country"], $config);
    if (!$countryId) {
        $countryId = dbs\createCountry($information["country"], $config);
    }

    // Check if the person already exists in the database
    if (dbs\personExists($information["name"], $information["surname"], $information["birth"], $config)) {
        $conn->rollback();
        $conn->close();
        return 0;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO persons (name, surname, organisation, sex, birth, death, country_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $birth = isset($information["birth"]) && $information["birth"] !== "" ? (int)$information["birth"] : 0;
    $death = isset($information["death"]) && $information["death"] !== "" ? (int)$information["death"] : null;
    $sex = mb_substr($information["sex"], 0, 1, 'UTF-8');
    
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
        $prizeDetailId = dbs\getPrizeDetails($information["language_en"], $information["language_sk"], $information["genre_en"], $information["genre_sk"], $config);
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
    $categorySK = dbs\convertCategory($categoryEN);
    

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
