<?php
require_once "../config.php";
require_once "functions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    $id = $_POST['_personId'];

    if ($method === "DELETE") {
        if(deletePerson($id, $dbconfig))
            echo "success";
        else 
            echo "failed";
    } else {

    }
}

function deletePerson($id, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return false;
    $conn->begin_transaction();

    $person = getEntryById($id, "persons", $config);
    $country = getEntryById($person["country_id"], "countries", $config);
    $prize = getEntryById($id, "prizes", $config);
    $prizeDetail = getEntryById($prize["prize_detail_id"], "prize_details", $config);

    try {
        // Delete from prizes
        $stmt = $conn->prepare("DELETE FROM prizes WHERE id = ?");
        $stmt->bind_param("s", $prize["id"]);
        $stmt->execute();

        // Delete from persons
        $stmt = $conn->prepare("DELETE FROM persons WHERE id = ?");
        $stmt->bind_param("s", $person["id"]);
        $stmt->execute();

        // Delete from persons
        if ($prizeDetail) {
            $stmt = $conn->prepare("DELETE FROM prize_details WHERE id = ?");
            $stmt->bind_param("s", $prizeDetail["id"]);
            $stmt->execute();
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        return false;
    }
    $conn->close();
    return true;
}

function getEntryById($id, $table, $config) {
    $conn = fnc\createMySqlConnection($config);
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
