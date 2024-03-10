<?php
require_once "../config.php";
require_once "functions.php";
require_once "dbsFunctions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    $id = $_POST['_personId'];

    if ($method === "DELETE") {
        if(deletePerson($id, $dbconfig))
            echo "success";
        else 
            echo "failed";
    } else {
        echo "edit";
    }
}

function deletePerson($id, $config) {
    $conn = fnc\createMySqlConnection($config);
    if (!$conn) return false;
    $conn->begin_transaction();

    $person = dbs\getEntryById($id, "persons", $config);
    $country = dbs\getEntryById($person["country_id"], "countries", $config);
    $prize = dbs\getEntryById($id, "prizes", $config);
    $prizeDetail = dbs\getEntryById($prize["prize_detail_id"], "prize_details", $config);

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
