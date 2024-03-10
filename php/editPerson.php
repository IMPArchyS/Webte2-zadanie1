<?php
require_once "../config.php";
require_once "functions.php";
require_once "dbsFunctions.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['_method'];
    $id = $_POST['_personId'];
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

    if (!dbs\checkInfo($result)) {
        echo "fail";
        exit();
    }

    if ($method === "PATCH") {
        if(editPerson($id, $result, $dbconfig))
            echo "success";
        else 
            echo "failed";
    }
}

function editPerson($id, $info, $config) {
    $conn = fnc\createMySqlConnection($config);
    $conn->begin_transaction();

    if (!empty($info["name"]) || !empty($info["surname"]) || !empty($info["birth"])) {
        $p = dbs\getPersonByCreds($info["name"], $info["surname"], $info["birth"] ,$config);
        if ($p) return false;
    }

    if (!empty($info["country"])) {
        $c = dbs\getCountryIdByName($info["country"], $config);
        $stmt = $conn->prepare("UPDATE persons SET country_id = ? WHERE id = ?");
        $stmt->bind_param("si", $c, $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["name"])) {
        $stmt = $conn->prepare("UPDATE persons SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $info["name"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["surname"])) {
        $stmt = $conn->prepare("UPDATE persons SET surname = ? WHERE id = ?");
        $stmt->bind_param("si", $info["surname"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }   

    if (!empty($info["org"])) {
        $stmt = $conn->prepare("UPDATE persons SET organisation = ? WHERE id = ?");
        $stmt->bind_param("si", $info["org"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }   

    if (!empty($info["sex"])) {
        $sex = substr($info["sex"], 0, 1);
        $stmt = $conn->prepare("UPDATE persons SET sex = ? WHERE id = ?");
        $stmt->bind_param("si", $sex, $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["birth"])) {
        $stmt = $conn->prepare("UPDATE persons SET birth = ? WHERE id = ?");
        $stmt->bind_param("si", $info["birth"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }
    
    if (!empty($info["death"])) {
        $stmt = $conn->prepare("UPDATE persons SET death = ? WHERE id = ?");
        $stmt->bind_param("si", $info["death"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["year"])) {
        $stmt = $conn->prepare("UPDATE prizes SET year = ? WHERE person_id = ?");
        $stmt->bind_param("si", $info["year"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["contribution_en"])) {
        $stmt = $conn->prepare("UPDATE prizes SET contribution_en = ? WHERE person_id = ?");
        $stmt->bind_param("si", $info["contribution_en"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if (!empty($info["contribution_sk"])) {
        $stmt = $conn->prepare("UPDATE prizes SET contribution_sk = ? WHERE person_id = ?");
        $stmt->bind_param("si", $info["contribution_sk"], $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    if ($info["category"] == "Literature") {
        $prizeDetailId = dbs\getPrizeDetails($info["language_en"], $info["language_sk"], $info["genre_en"], $info["genre_sk"], $config);
        if (!$prizeDetailId) {
            $stmt = $conn->prepare("INSERT INTO prize_details (language_en, language_sk, genre_en, genre_sk) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $info["language_en"], $info["language_sk"], $info["genre_en"], $info["genre_sk"]);
            
            if (!$stmt->execute()) {
                $conn->rollback();
                $stmt->close();
                $conn->close();
                return false;
            }
            $prizeDetailId = $conn->insert_id;

            $stmt = $conn->prepare("UPDATE prizes SET prize_detail_id = ? WHERE person_id = ?");
            $stmt->bind_param("si", $prizeDetailId, $id);
            if (!$stmt->execute()) {
                $conn->rollback();
                $stmt->close();
                $conn->close();
                return false;
            }
        }
    }
    $categoryEN = $info["category"];
    $categorySK = dbs\convertCategory($categoryEN);

    if (!empty($info["category"])) {
        $stmt = $conn->prepare("UPDATE prizes SET category = ? WHERE person_id = ?");
        $stmt->bind_param("si", $categorySK, $id);
        if (!$stmt->execute()) {
            $conn->rollback();
            $stmt->close();
            $conn->close();
            return false;
        }
    }

    $conn->commit();
    $stmt->close();
    $conn->close();
    return true;
}